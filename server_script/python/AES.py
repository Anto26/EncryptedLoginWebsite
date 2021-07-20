#!/usr/bin/env python

import sys

# Import local AES functions
from AES_base import *

# Load CLI arguments
try:
	# AES Initial Vector as hexadecimal
	hex_AES_IV = sys.argv[1]

	# AES key as hexadecimal
	hex_AES_key = sys.argv[2]

	# AES mode of function
	# 	for encryption --encrypt or -e
	# 	for decryption --decrypt or -d
	mode = sys.argv[3];

	# Check if entered AES mode is allowed
	if mode not in ("--encrypt", "-e", "--decrypt", "-d"):
		raise Exception()

	# String value to encrypt or decrypt
	value = sys.argv[4];
except:
    print("Error that regards arguments!")
    sys.exit(1)

# AES Initial Vector as array of bytes
IV = bytearray.fromhex(hex_AES_IV)

# AES Initial Vector as array of bytes
key = bytearray.fromhex(hex_AES_key)

# If in encryption mode
if mode in ("--encrypt", "-e"):

	# Convert string to bytes
	bytes_value = bytes(value, 'utf-8')

	# Encrypt, obtaining a bytes value
	rawResult = encryptToBase64(bytes_value, key, IV)
# If in decryption mode
else:
	# Decrypt, obtaining a bytes value
	rawResult = decryptFromBase64(value, key, IV)

# Convert bytes value to unicode utf-8 string
result = rawResult.decode('utf-8')

# Output the result
print(result)