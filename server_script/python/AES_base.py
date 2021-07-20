#!/usr/bin/env python

from Crypto.Cipher import AES
from Crypto.Util.Padding import pad, unpad

import base64

BLOCK_SIZE = 16

# Function that encrypt a message using AES chiper
#	input message: bytes or bytearray message 
#	input key and IV: bytes or bytearrays values
#	output: message of bytes type
def encryptToBase64(message, key, IV):

	# Set up the AES object in CBC mode
	aes = AES.new(key, AES.MODE_CBC, iv=IV)

	# Add padding according to pkcs7
	message_with_pad = pad(message, BLOCK_SIZE, 'pkcs7')

	# Encrypt the message
	encrypted = aes.encrypt(message_with_pad)

	# Convert encrypted message from bytes to base64
	base64_encrypted = base64.b64encode(encrypted)

	return base64_encrypted

# Function that decrypt a message using AES chiper
#	input message: base64 normal string (e.g. "A1B3Q/=")
#	input key and IV: bytes or bytearrays values
#	output: bytes message
def decryptFromBase64(encrypted_base64, key, IV):

	# Set ut the AES object in CBC mode
	aes = AES.new(key, AES.MODE_CBC, iv=IV)

	# Convert encrypted message from bytes to base64
	encrypted = base64.b64decode(encrypted_base64)

	# Decrypt the message
	decrypted_with_pad = aes.decrypt(encrypted)

	# Remove padding according to pkcs7
	decrypted_no_pad = unpad(decrypted_with_pad, BLOCK_SIZE, 'pkcs7')

	return decrypted_no_pad