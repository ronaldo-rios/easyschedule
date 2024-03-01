FROM mysql:latest

# Copy the database schema to the container
ADD ./init.sql /docker-entrypoint-initdb.d/

# Expose the default MySQL port
EXPOSE 3306

# Set the default command to run when starting the container
CMD ["mysqld"]