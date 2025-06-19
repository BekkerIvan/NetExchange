# Use Node.js as the base image
FROM node:alpine

# Set the working directory
WORKDIR /app

# Copy package files and install dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy the rest of the application files
COPY . .

# Build the Svelte application
# RUN npm run build

# Expose the port the app will run on
EXPOSE 5000

# Start the application
CMD ["npm", "run", "dev"]