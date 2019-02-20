FROM ubuntu:18.04

MAINTAINER ted@digistate.nl

ENV DEBIAN_FRONTEND noninteractive

#Set variables
ENV DBUSER=ebooklib
ENV DBPASS=ebooklib
ENV DBNAME=ebooklib

#Update repo and install depandancies
RUN apt-get update && \
#    echo "mariadb-server-10.1 mariadb-server-10.1/root_password password $DBPASS" | debconf-set-selections && \
#    echo "mariadb-server-10.1 mariadb-server-10.1/root_password_again password $DBPASS" | debconf-set-selections && \
    apt-get -y install php7.2-mysqli php7.2-mbstring php7.2-dom \
                        php7.2 libapache2-mod-php7.2 php7.2-cli php7.2-gd \
                        php7.2-zip php7.2-xml php7.2-curl \
			zip curl git supervisor sudo

RUN apt-get -y install software-properties-common && \
    apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8 && \
    add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://ftp.nluug.nl/db/mariadb/repo/10.2/ubuntu bionic main' && \
    apt update && apt-get -y install mariadb-server

#Fix apache config
COPY ebooklib.local.conf /etc/apache2/sites-available/ebooklib.local.conf
RUN a2dissite 000-default && \
    a2ensite ebooklib.local && \
    a2enmod rewrite

#Downloading and installing ccomposer with curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global require hirak/prestissimo --no-plugins --no-scripts

#Application install
RUN /etc/init.d/mysql start && \
    mysql -e "CREATE DATABASE $DBNAME;" && \
    mysql -e "CREATE USER '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';" && \
    mysql -e "GRANT ALL PRIVILEGES ON $DBNAME.* TO '$DBUSER'@'localhost';" && \
    chown www-data:www-data /var/www && \
    sudo -u www-data COMPOSER_CACHE_DIR=/dev/null composer create-project --remove-vcs --stability=beta tedvdb/ebooklib /var/www/ebooklib && \
    sed -i "s/DB_DATABASE.*/DB_DATABASE=$DBNAME"/ /var/www/ebooklib/.env && \
    sed -i "s/DB_USERNAME.*/DB_USERNAME=$DBUSER"/ /var/www/ebooklib/.env && \
    sed -i "s/DB_PASSWORD.*/DB_PASSWORD=$DBPASS"/ /var/www/ebooklib/.env && \
    sed -i "s/'engine'.*/'engine' => 'InnoDB',"/ /var/www/ebooklib/config/database.php && \ 
    sudo -u www-data php /var/www/ebooklib/artisan key:generate && \
    sudo -u www-data php /var/www/ebooklib/artisan migrate && \
    sudo -u www-data php /var/www/ebooklib/artisan db:seed

EXPOSE 80
VOLUME /var/lib/mysql
VOLUME /var/www/ebooklib/storage

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD supervisord
