FROM node:alpine

RUN apk add --update sudo \
		     yarn
RUN yarn add --dev @symfony/webpack-encore

USER root

RUN mkdir -p /yarn

RUN chmod -R 777 /yarn

USER node

WORKDIR /app
