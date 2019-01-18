#Batch to del users in linux system
#Author : zhanglei
#E-mail : ceocreator@126.com
#!/bin/bash
echo
num=0
username="iwantpad"
tmpuser=""
userpasswd=`./pcrypt "123456"`
for ((i=1; i<=25; i++)) #range is 1 2 3...25
do
#printf -v num "%.3d" $i
#userno=$num
tmpuser="$username$i"
userdel -r $tmpuser
useradd -m $tmpuser -g mail -p $userpasswd -s "/sbin/nologin"
echo  "$tmpuser---$userpasswd"
echo "********************************************"
done