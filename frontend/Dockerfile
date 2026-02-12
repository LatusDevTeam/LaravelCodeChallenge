FROM node:20

WORKDIR /usr/src/app

# Ensure dev dependencies are installed by setting NODE_ENV to development
ENV NODE_ENV=development

# Install dependencies first by copying only package manifests to leverage Docker layer caching
COPY package.json package-lock.json ./

# Use npm ci for reproducible installs (uses package-lock.json). Install dev deps explicitly.
RUN npm ci --silent --include=dev

# Copy the rest of the source
COPY . ./

EXPOSE 5173

# Default command to run the dev server; change as needed for production builds
CMD ["npm", "run", "dev"]
