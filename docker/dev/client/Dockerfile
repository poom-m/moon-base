# Image
FROM node:14.16.1-alpine

# Add python and other dependencies
RUN apk --no-cache add --virtual native-deps \
    g++ gcc libgcc libstdc++ linux-headers make python2 && \
    npm install --quiet node-gyp -g

# Set up work directory
WORKDIR /var/www/client

# Configure host
ENV HOST 0.0.0.0

# Init command
CMD ["sh", "-c", "yarn install && yarn dev"]
