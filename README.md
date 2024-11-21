# Website Creation Helper

A PHP package to quickly scaffold new web projects with popular CSS frameworks and modern development tools.

## 📋 Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Features](#features)
- [Available Commands](#available-commands)
- [Framework Support](#framework-support)
- [Templates](#templates)
- [Configuration](#configuration)
- [Examples](#examples)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

## 🚀 Installation

!!!! USE ./bin/wch if you got an error with the wch command !!!!

### Via Composer

\`\`\`bash
composer require wecode/website-creation-helper
\`\`\`

### Requirements

- PHP 8.0 or higher
- Node.js 14+ and npm/yarn
- Composer 2.0+

## ⚡ Quick Start

\`\`\`bash
# Create a new project with Bootstrap 5
wch new my-project --framework=bootstrap --version=5.3

# Create a project with Tailwind CSS
wch new my-project-tailwind --framework=tailwind --version=3.0

# Install all dependencies
cd my-project
wch install
\`\`\`

## 🎯 Features

- **Project Scaffolding**
  - Automated directory structure creation
  - Configuration files setup
  - Git initialization
  - README generation

- **Framework Integration**
  - Bootstrap (v5.3, v4.6)
  - Tailwind CSS (v3.0, v2.0)
  - Foundation (v6.7)
  - Bulma (v0.9)

- **Template System**
  - Twig templating engine
  - Custom template support
  - Component-based architecture
  - Reusable layouts

- **Asset Management**
  - CSS/SCSS compilation
  - JavaScript bundling
  - Image optimization
  - Font management

- **Development Tools**
  - Live reload
  - CSS preprocessing
  - JS transpilation
  - Source maps

## 🛠️ Available Commands

### Project Creation
\`\`\`bash
# Basic project creation
wch new project-name

# With specific framework
wch new project-name --framework=bootstrap

# With framework version
wch new project-name --framework=bootstrap --version=5.3
\`\`\`

### Template Management
\`\`\`bash
# Create new template
wch template:create my-template

# List available templates
wch template:list

# Export template
wch template:export my-template
\`\`\`

### Asset Management
\`\`\`bash
# Install dependencies
wch install

# Build assets
wch build

# Watch for changes
wch watch
\`\`\`

## 🎨 Framework Support

### Bootstrap
- Versions: 5.3, 4.6
- Features:
  - Grid system
  - Components
  - Utilities
  - JavaScript plugins

### Tailwind CSS
- Versions: 3.0, 2.0
- Features:
  - JIT compilation
  - Custom configuration
  - PurgeCSS integration
  - Plugin support

### Foundation
- Version: 6.7
- Features:
  - XY Grid
  - Motion UI
  - Building blocks
  - Responsive design

### Bulma
- Version: 0.9
- Features:
  - Flexbox based
  - Modular architecture
  - No JavaScript dependencies
  - Modern syntax

## 📝 Templates

### Default Templates
- `base.twig`: Basic HTML5 structure
- `landing.twig`: Landing page template
- `blog.twig`: Blog layout
- `admin.twig`: Admin dashboard

### Custom Templates
\`\`\`bash
# Template structure
templates/
├── custom/
│   ├── my-template.twig
│   └── components/
├── layouts/
└── partials/
\`\`\`

## ⚙️ Configuration

### Project Configuration
\`\`\`yaml
# config.yaml
project:
  name: "My Website"
  framework: "bootstrap"
  version: "5.3"
  assets:
    compile: true
    minify: true
    sourcemaps: true
\`\`\`

### Framework Configuration
\`\`\`php
// frameworks.php
return [
    'css_frameworks' => [
        'bootstrap' => [
            'versions' => ['5.3', '4.6'],
            'cdn' => 'https://cdn.jsdelivr.net/npm/bootstrap@{version}'
        ],
        // ... other frameworks
    ]
];
\`\`\`

## 📚 Examples

### Basic Website
\`\`\`bash
wch new my-website
cd my-website
wch install
wch serve
\`\`\`

### Custom Template Usage
\`\`\`bash
# Create template
wch template:create blog-post

# Use template
wch generate:page about --template=blog-post
\`\`\`

## 🔧 Troubleshooting

### Common Issues

1. **npm not found**
   \`\`\`bash
   curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
   sudo apt-get install -y nodejs
   \`\`\`

2. **Permission Issues**
   \`\`\`bash
   sudo chown -R $USER:$USER .
   \`\`\`

3. **Framework Installation Failed**
   - Check internet connection
   - Verify npm registry access
   - Clear npm cache: \`npm cache clean --force\`
MIT License