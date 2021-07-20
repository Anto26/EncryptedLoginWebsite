# EncryptedLoginWebsite
A website with the login and registration procedures secured by a hybrid cryptosystem system.

Hybrid cryptosystem components:
  - Asymmetrical one: RSA, with 2048-bit keys (self-implemented in PHP, using just the GMP library);
  - Symmetrical one: AES, with a 256-bit private key (not self-implemented).

The server-side symmetric-encrypted communication, which is the default one once the AES private key has been exchanged asymmetrically, is ensured by two Python scripts reached through a PHP function.
