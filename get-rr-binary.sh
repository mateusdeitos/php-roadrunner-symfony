#!/bin/bash
DIR="$( cd "$( dirname "$0" )" && pwd )"
# if 'rr' exists, then do nothing, else get the binary
if [ -f "$DIR/rr" ]; then
	echo "rr binary exists"
else
	echo "Downloading rr binary"
	$DIR/vendor/bin/rr get-binary
fi

