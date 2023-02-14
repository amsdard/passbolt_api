FROM passbolt/passbolt:latest

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install libldap2-dev -y && \
    apt-get install -y php-ldap && \
    apt-get install -y git && \
    apt-get install -y zip unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
