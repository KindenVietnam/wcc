# run php script in server
cd /var/www/ivs/user/admin/att_pdf
MONTH=$(date +'%m')
php /var/www/ivs/user/admin/print_to_pdf.php thang=$MONTH
