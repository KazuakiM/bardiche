#!/bin/sh
#--------------------------------
# @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
# @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
# @license   http://www.opensource.org/licenses/mit-license.php  MIT License
#
# @link      https://github.com/KazuakiM/bardiche
#--------------------------------
# ssh {{{
ssh-keygen -t rsa -f $HOME/.ssh/id_rsa -N "" -q
cat $HOME/.ssh/id_rsa.pub    >> $HOME/.ssh/authorized_keys
ssh-keyscan -t rsa localhost >> $HOME/.ssh/known_hosts
export SSH_PRIVATE_KEY="$HOME/.ssh/id_rsa"
export SSH_PUBLIC_KEY="$HOME/.ssh/id_rsa.pub"
#}}}

# ftp {{{
mkdir -p /tmp/ftp
chown $(id -u).$(id -g) /tmp/ftp
echo pass | ftpasswd --file /tmp/ftpd.passwd --passwd --name fate --uid $(id -u) -gid $(id -g) --home /tmp/ftp --shell /bin/sh --stdin
ftpasswd --file /tmp/ftpd.group --group --name nogroup --gid $(id -g)
cat <<EOS > /tmp/proftpd.conf
DefaultAddress 127.0.0.1
Port 10021
AuthUserFile /tmp/ftpd.passwd
AuthGroupFile /tmp/ftpd.group
AuthOrder mod_auth_file.c
DefaultRoot ~
ListOptions -a
EOS
sudo /usr/sbin/proftpd -c /tmp/proftpd.conf -S 127.0.0.1
#}}}
