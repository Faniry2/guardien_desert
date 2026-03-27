// public/js/crypto.js
// Zero-Knowledge Crypto Layer — Renaît-Sens 90
// AES-256-GCM + PBKDF2-SHA256
// La clé ne quitte JAMAIS le navigateur.

;(function () {
    'use strict';

    const PBKDF2_ITERATIONS = 310_000;
    const CHECK_PLAINTEXT   = 'renait-sens-check-v1';
    const SESSION_KEY_NAME  = '__rs_jk';

    // ─── Utilitaires ──────────────────────────────────────────────
    function hexToBytes(hex) {
        const arr = new Uint8Array(hex.length / 2);
        for (let i = 0; i < arr.length; i++) {
            arr[i] = parseInt(hex.substr(i * 2, 2), 16);
        }
        return arr;
    }

    function bytesToHex(buf) {
        return Array.from(new Uint8Array(buf))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
    }

    function base64ToBytes(b64) {
        return Uint8Array.from(atob(b64), c => c.charCodeAt(0));
    }

    function bytesToBase64(buf) {
        return btoa(String.fromCharCode(...new Uint8Array(buf)));
    }

    // ─── Génération du salt ───────────────────────────────────────
    async function generateSalt() {
        const salt = crypto.getRandomValues(new Uint8Array(32));
        return bytesToHex(salt);
    }

    // ─── Dérivation PBKDF2 ────────────────────────────────────────
    async function deriveKey(passphrase, saltHex) {
        const keyMaterial = await crypto.subtle.importKey(
            'raw',
            new TextEncoder().encode(passphrase),
            'PBKDF2',
            false,
            ['deriveKey']
        );
        return crypto.subtle.deriveKey(
            {
                name:       'PBKDF2',
                salt:       hexToBytes(saltHex),
                iterations: PBKDF2_ITERATIONS,
                hash:       'SHA-256',
            },
            keyMaterial,
            { name: 'AES-GCM', length: 256 },
            true,                          // extractable → pour sessionStorage
            ['encrypt', 'decrypt']
        );
    }

    // ─── Vérification de clé (HMAC) ───────────────────────────────
    async function computeKeyCheckHash(cryptoKey) {
        const rawKey = await crypto.subtle.exportKey('raw', cryptoKey);
        const hmacKey = await crypto.subtle.importKey(
            'raw', rawKey, { name: 'HMAC', hash: 'SHA-256' }, false, ['sign']
        );
        const sig = await crypto.subtle.sign(
            'HMAC',
            hmacKey,
            new TextEncoder().encode(CHECK_PLAINTEXT)
        );
        return bytesToBase64(sig);
    }

    async function verifyKey(cryptoKey, storedHash) {
        const computed = await computeKeyCheckHash(cryptoKey);
        return computed === storedHash;
    }

    // ─── Chiffrement AES-256-GCM ──────────────────────────────────
    async function encrypt(plaintext, cryptoKey) {
        const iv      = crypto.getRandomValues(new Uint8Array(12));
        const encoded = new TextEncoder().encode(plaintext);

        const ciphertext = await crypto.subtle.encrypt(
            { name: 'AES-GCM', iv },
            cryptoKey,
            encoded
        );

        // Stocker [iv (12) | ciphertext] encodé en base64
        const combined = new Uint8Array(12 + ciphertext.byteLength);
        combined.set(iv);
        combined.set(new Uint8Array(ciphertext), 12);
        return bytesToBase64(combined);
    }

    // ─── Déchiffrement AES-256-GCM ────────────────────────────────
    async function decrypt(encryptedBase64, cryptoKey) {
        const combined  = base64ToBytes(encryptedBase64);
        const iv        = combined.slice(0, 12);
        const ciphertext = combined.slice(12);

        const decrypted = await crypto.subtle.decrypt(
            { name: 'AES-GCM', iv },
            cryptoKey,
            ciphertext
        );
        return new TextDecoder().decode(decrypted);
    }

    // ─── Session key (sessionStorage) ────────────────────────────
    async function storeSessionKey(cryptoKey) {
        const jwk = await crypto.subtle.exportKey('jwk', cryptoKey);
        sessionStorage.setItem(SESSION_KEY_NAME, JSON.stringify(jwk));
    }

    async function getSessionKey() {
        const raw = sessionStorage.getItem(SESSION_KEY_NAME);
        if (!raw) return null;
        try {
            const jwk = JSON.parse(raw);
            return crypto.subtle.importKey(
                'jwk',
                jwk,
                { name: 'AES-GCM' },
                true,
                ['encrypt', 'decrypt']
            );
        } catch {
            return null;
        }
    }

    function lockJournal() {
        sessionStorage.removeItem(SESSION_KEY_NAME);
    }

    // ─── Setup initial (première configuration) ───────────────────
    async function setupEncryption(passphrase) {
        const saltHex  = await generateSalt();
        const key      = await deriveKey(passphrase, saltHex);
        const checkHash = await computeKeyCheckHash(key);

        // Envoyer salt + hash au serveur (JAMAIS la clé)
        const response = await fetch('/api/encryption-setup', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
            },
            body: JSON.stringify({ salt_hex: saltHex, key_check_hash: checkHash }),
        });

        if (!response.ok) throw new Error('Setup failed');

        await storeSessionKey(key);
        return { saltHex, checkHash };
    }

    // ─── Export global ───────────────────────────────────────────
    window.JournalCrypto = {
        generateSalt,
        deriveKey,
        computeKeyCheckHash,
        verifyKey,
        encrypt,
        decrypt,
        storeSessionKey,
        getSessionKey,
        lockJournal,
        setupEncryption,
    };
})();
