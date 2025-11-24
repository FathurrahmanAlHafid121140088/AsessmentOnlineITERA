#!/bin/bash

##############################################################################
# VPS Server Setup Script for Laravel
#
# Script untuk setup environment VPS dari awal (Ubuntu 20.04/22.04)
# Install: PHP 8.2, Nginx, Composer, Node.js, MySQL (optional)
#
# Usage:
#   sudo bash setup-server.sh
#   sudo bash setup-server.sh --with-mysql    # Include MySQL installation
#
##############################################################################

set -e  # Exit on error

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }
print_header() {
    echo ""
    echo -e "${BLUE}=====================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}=====================================${NC}"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Script ini harus dijalankan dengan sudo"
    print_info "Usage: sudo bash setup-server.sh"
    exit 1
fi

# Check Ubuntu version
check_ubuntu_version() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        if [[ "$ID" != "ubuntu" ]]; then
            print_warning "This script is designed for Ubuntu. Your OS: $ID"
            read -p "Continue anyway? (yes/no): " confirm
            if [ "$confirm" != "yes" ]; then
                exit 1
            fi
        fi
    fi
}

# Install MySQL option
INSTALL_MYSQL=false
if [ "$1" == "--with-mysql" ]; then
    INSTALL_MYSQL=true
    print_info "MySQL akan diinstall"
fi

main() {
    print_header "🚀 VPS Server Setup untuk Laravel"

    check_ubuntu_version

    # Step 1: Update System
    print_header "📦 Step 1: Update System"
    print_info "Updating package lists..."
    apt update
    print_info "Upgrading packages..."
    apt upgrade -y
    print_success "System updated"

    # Step 2: Install Essential Tools
    print_header "🛠️ Step 2: Install Essential Tools"
    print_info "Installing essential packages..."
    apt install -y software-properties-common \
        curl wget git unzip vim \
        ca-certificates apt-transport-https
    print_success "Essential tools installed"

    # Step 3: Install PHP 8.2
    print_header "🐘 Step 3: Install PHP 8.2"
    print_info "Adding PHP repository..."
    add-apt-repository ppa:ondrej/php -y
    apt update

    print_info "Installing PHP 8.2 and extensions..."
    apt install -y php8.2 \
        php8.2-fpm \
        php8.2-cli \
        php8.2-common \
        php8.2-mysql \
        php8.2-xml \
        php8.2-curl \
        php8.2-mbstring \
        php8.2-zip \
        php8.2-gd \
        php8.2-bcmath \
        php8.2-sqlite3 \
        php8.2-intl \
        php8.2-readline

    print_success "PHP 8.2 installed"
    php -v

    # Configure PHP
    print_info "Configuring PHP..."
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' /etc/php/8.2/fpm/php.ini
    sed -i 's/post_max_size = .*/post_max_size = 20M/' /etc/php/8.2/fpm/php.ini
    sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.2/fpm/php.ini
    sed -i 's/max_execution_time = .*/max_execution_time = 300/' /etc/php/8.2/fpm/php.ini

    systemctl enable php8.2-fpm
    systemctl start php8.2-fpm
    print_success "PHP configured and started"

    # Step 4: Install Nginx
    print_header "🌐 Step 4: Install Nginx"
    print_info "Installing Nginx..."
    apt install -y nginx
    systemctl enable nginx
    systemctl start nginx
    print_success "Nginx installed and started"

    # Step 5: Install Composer
    print_header "📦 Step 5: Install Composer"
    print_info "Downloading Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    print_success "Composer installed"
    composer --version

    # Step 6: Install Node.js & npm
    print_header "📗 Step 6: Install Node.js 20.x"
    print_info "Adding Node.js repository..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

    print_info "Installing Node.js..."
    apt install -y nodejs
    print_success "Node.js installed"
    node -v
    npm -v

    # Step 7: Install MySQL (Optional)
    if [ "$INSTALL_MYSQL" = true ]; then
        print_header "🗄️ Step 7: Install MySQL"
        print_info "Installing MySQL Server..."
        apt install -y mysql-server

        systemctl enable mysql
        systemctl start mysql

        print_success "MySQL installed"
        print_warning "IMPORTANT: Run 'mysql_secure_installation' untuk setup security!"
        print_info "Kemudian buat database:"
        echo "  sudo mysql -u root -p"
        echo "  CREATE DATABASE assessment_db;"
        echo "  CREATE USER 'assessment_user'@'localhost' IDENTIFIED BY 'password';"
        echo "  GRANT ALL PRIVILEGES ON assessment_db.* TO 'assessment_user'@'localhost';"
        echo "  FLUSH PRIVILEGES;"
    fi

    # Step 8: Setup Firewall
    print_header "🔥 Step 8: Configure Firewall (UFW)"
    print_info "Installing and configuring UFW..."
    apt install -y ufw

    # Allow essential services
    ufw allow OpenSSH
    ufw allow 'Nginx Full'

    # Enable firewall
    print_warning "Enabling firewall..."
    echo "y" | ufw enable

    ufw status
    print_success "Firewall configured"

    # Step 9: Setup Swap (if < 2GB RAM)
    print_header "💾 Step 9: Check Swap Memory"
    TOTAL_RAM=$(free -m | awk '/^Mem:/{print $2}')
    SWAP_EXISTS=$(free -m | awk '/^Swap:/{print $2}')

    if [ $TOTAL_RAM -lt 2048 ] && [ $SWAP_EXISTS -lt 512 ]; then
        print_info "Low RAM detected ($TOTAL_RAM MB), creating swap file..."
        fallocate -l 1G /swapfile
        chmod 600 /swapfile
        mkswap /swapfile
        swapon /swapfile
        echo '/swapfile none swap sw 0 0' >> /etc/fstab
        print_success "1GB Swap created"
    else
        print_info "RAM: ${TOTAL_RAM}MB, Swap: ${SWAP_EXISTS}MB - OK"
    fi

    # Step 10: Create Laravel directory
    print_header "📁 Step 10: Prepare Web Directory"
    if [ ! -d /var/www ]; then
        mkdir -p /var/www
    fi
    chown -R www-data:www-data /var/www
    chmod -R 755 /var/www
    print_success "Web directory prepared: /var/www"

    # Final Summary
    print_header "🎉 Server Setup Completed!"
    echo ""
    print_info "Installed Software:"
    echo "  ✓ PHP $(php -v | head -n 1 | awk '{print $2}')"
    echo "  ✓ Nginx $(nginx -v 2>&1 | awk '{print $3}')"
    echo "  ✓ Composer $(composer --version | awk '{print $3}')"
    echo "  ✓ Node.js $(node -v)"
    echo "  ✓ npm $(npm -v)"
    if [ "$INSTALL_MYSQL" = true ]; then
        echo "  ✓ MySQL $(mysql --version | awk '{print $3}')"
    fi
    echo ""

    print_info "Service Status:"
    systemctl is-active --quiet nginx && echo "  ✓ Nginx: Running" || echo "  ✗ Nginx: Stopped"
    systemctl is-active --quiet php8.2-fpm && echo "  ✓ PHP-FPM: Running" || echo "  ✗ PHP-FPM: Stopped"
    if [ "$INSTALL_MYSQL" = true ]; then
        systemctl is-active --quiet mysql && echo "  ✓ MySQL: Running" || echo "  ✗ MySQL: Stopped"
    fi
    echo ""

    print_success "Server ready untuk Laravel deployment!"
    echo ""
    print_info "Next Steps:"
    echo "  1. Clone project ke /var/www/"
    echo "     cd /var/www"
    echo "     git clone <repo-url> AsessmentOnline"
    echo ""
    echo "  2. Follow DEPLOYMENT_GUIDE.md untuk deploy aplikasi"
    echo ""
    if [ "$INSTALL_MYSQL" = true ]; then
        print_warning "  3. IMPORTANT: Setup MySQL security"
        echo "     sudo mysql_secure_installation"
        echo ""
    fi

    print_info "Useful Commands:"
    echo "  - Restart Nginx: sudo systemctl restart nginx"
    echo "  - Restart PHP-FPM: sudo systemctl restart php8.2-fpm"
    echo "  - Check logs: sudo tail -f /var/log/nginx/error.log"
    echo "  - Firewall status: sudo ufw status"
    echo ""
}

# Cleanup on error
cleanup() {
    print_error "Setup failed!"
    print_info "Check error messages above"
}

trap cleanup ERR

# Run main setup
main "$@"
