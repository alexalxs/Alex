#!/bin/bash
#Recipient=âalexalxs@gmail.com
#Subject=â"Greetingsdfasfa"
#Message=â"welcome to our sitasdfasdfse"
#mail -s $Subject alexalxs@gmail.com <<< $Message
#ls
#mail -s "subject here" alexalxs@gmail.com <<< "message"

#!/bin/sh
# Script that checks whether apache is still up, and if not:
# - e-mail the last bit of log files
# - kick some life back into it
# -- Thomas, 20050606

PATH=/bin:/usr/bin
THEDIR=/tmp/apache-watchdog
EMAIL=alexalxs@gmail.com
mkdir -p $THEDIR

if ( wget --timeout=30 -q -P $THEDIR http://145.14.157.98/)


#if ( wget --timeout=30 -q -P $THEDIR https://insta.gorilla.digital/phpinfo.php)
then
	 # we are up
	if [[ -f ~/.apache-was-up ]]
	then
					touch ~/.apache-was-up2
	else
					touch ~/.apache-was-up
	fi
else
		# down! but if it was down already, don't keep spamming
	if [[ -f ~/.apache-was-up2 ]]
	then
				rm ~/.apache-was-up2
	else
		if [[ -f ~/.apache-was-up ]]
		then
						# write a nice e-mail
						echo -n "apache crashed at " > $THEDIR/mail
						date >> $THEDIR/mail
						echo >> $THEDIR/mail
						echo "Access log:" >> $THEDIR/mail
						tail -n 30 /var/log/apache2_access/current >> $THEDIR/mail
						echo >> $THEDIR/mail
						echo "Error log:" >> $THEDIR/mail
						tail -n 30 /var/log/apache2_error/current >> $THEDIR/mail
						echo >> $THEDIR/mail
						# kick apache
						echo "Now kicking apache..." >> $THEDIR/mail
						/etc/init.d/apache2 stop >> $THEDIR/mail 2>&1
						killall -9 apache2 >> $THEDIR/mail 2>&1
						/etc/init.d/apache2 start >> $THEDIR/mail 2>&1
						# send the mail
						echo >> $THEDIR/mail
						echo "Good luck troubleshooting!" >> $THEDIR/mail
						mail -s "apache-watchdog: apache crashed" $EMAIL < $THEDIR/mail
						rm ~/.apache-was-up
		fi
	fi
fi
rm -rf $THEDIR
