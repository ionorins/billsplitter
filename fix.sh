for file in $(find . | grep ".php\|.css\|.js"); do
    perm=$(ls -ld $file)

    if [ ${perm:1:10} != "rw-r--r--" ]; then
        if [ "${perm:0:1}" = "-" ]; then
            echo File $file has the wrong permission: ${perm:1:10}
            echo This has been changed to: -rw-r--r--
            chmod -R 644 "$file"
        fi
    fi
done

for file in $(find . ); do
    perm=$(ls -ld $file)
    if [ ${perm:1:10} != "rwxr-xr-x" ]; then
        if [ "${perm:0:1}" = "d" ]; then
            echo Directory $file has the wrong permission: ${perm:1:10}
            echo This has been changed to: drwxr-xr-x
            chmod -R 755 "$file"
        fi
    fi
done
