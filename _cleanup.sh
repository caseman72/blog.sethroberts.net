#!/bin/bash
#

files=''
files=`/usr/bin/find . | /usr/bin/grep '?' | /usr/bin/grep 'attachment\_id=[0-9]*[%]2F'`;

list=($files)
for i in "${list[@]}"; do
	echo rm '"'$i'"'
done

files=''
files=`/usr/bin/find . | /usr/bin/grep 'edit\.php'`;

list=($files)
for i in "${list[@]}"; do
	echo rm '"'$i'"'
done

files=''
files=`/usr/bin/find . | /usr/bin/grep 'login\.php'`;

list=($files)
for i in "${list[@]}"; do
	echo rm '"'$i'"'
done

files=''
files=`/usr/bin/find . | /usr/bin/grep '?' | /usr/bin/grep 'preview'`;
list=($files)
for i in "${list[@]}"; do
	echo mv '"'$i'"'  '"'${i//\?preview=[a-z.-]*/}'"';
done

files=''
files=`/usr/bin/find . | /usr/bin/grep '?' | /usr/bin/grep 'v='`;

list=($files)
for i in "${list[@]}"; do
	echo mv '"'$i'"'  '"'${i//\?v=[0-9.]*/}'"';
done

files=''
files=`/usr/bin/find . | /usr/bin/grep '?' | /usr/bin/grep 'ver='`;

list=($files)
for i in "${list[@]}"; do
	echo mv '"'$i'"'  '"'${i//\?ver=[0-9.]*/}'"';
done

files=''
files=`/usr/bin/find . | /usr/bin/grep 'xmlrpc\.php'`;

list=($files)
for i in "${list[@]}"; do
	echo rm '"'$i'"'
done

