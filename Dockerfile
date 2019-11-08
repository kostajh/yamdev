FROM docker-registry.wikimedia.org/wikimedia/mediawiki-core:dev

USER root
RUN apt-get update && \
	apt-get install php-redis mysql-client -y

USER somebody
/install.sh
