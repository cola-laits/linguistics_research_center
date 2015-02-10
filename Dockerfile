FROM docker-registry.la.utexas.edu/base_laravel:latest
 
ADD server /var/www/html/
 
ADD start.sh /root/start.sh
RUN chmod +x /root/start.sh
CMD ["/root/start.sh"]