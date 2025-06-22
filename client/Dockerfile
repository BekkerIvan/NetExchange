FROM node:alpine as npm
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install

FROM node:alpine

WORKDIR /app

COPY --from=npm /app/node_modules /app/node_modules
COPY . .

EXPOSE 80

# Start the application
CMD ["npm", "run", "dev"]