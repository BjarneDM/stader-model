#!/usr/bin/bash

while read -u 9 fileName
do
    echo ${fileName}
    sed -i'' -E \
		-e '/set_include_path/s/Volumes/home/' \
		-e '/set_include_path/s/Bjarne/bjarne/' \
		${fileName}
done 9< \
	<( find . -name '*.php' )
