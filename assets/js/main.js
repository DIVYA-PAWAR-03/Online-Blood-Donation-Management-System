// Blood Donation Management System - Main JavaScript

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200; // Animation speed

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const count = parseInt(counter.innerText);
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(() => animateCounters(), 1);
            } else {
                counter.innerText = target.toLocaleString();
            }
        });
    }

    // Intersection Observer for counter animation
    const counterSection = document.querySelector('.stats-card');
    if (counterSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(counterSection.parentElement);
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Phone number formatting
    var phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            // Remove non-digits
            var value = e.target.value.replace(/\D/g, '');
            
            // Limit to 10 digits
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            
            e.target.value = value;
        });
    });

    // Form validation enhancement
    var forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Blood type compatibility checker
    var bloodTypeSelect = document.getElementById('blood_type');
    if (bloodTypeSelect) {
        bloodTypeSelect.addEventListener('change', function() {
            showBloodTypeCompatibility(this.value);
        });
    }

    // Search form auto-submit delay
    var searchInputs = document.querySelectorAll('.auto-search');
    searchInputs.forEach(function(input) {
        var timeoutId;
        input.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(function() {
                input.closest('form').submit();
            }, 1000);
        });
    });

    // Smooth scrolling for anchor links
    var anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Blood type compatibility information
function showBloodTypeCompatibility(bloodType) {
    const compatibility = {
        'A+': {
            canDoneTo: ['A+', 'AB+'],
            canReceiveFrom: ['A+', 'A-', 'O+', 'O-']
        },
        'A-': {
            canDoneTo: ['A+', 'A-', 'AB+', 'AB-'],
            canReceiveFrom: ['A-', 'O-']
        },
        'B+': {
            canDoneTo: ['B+', 'AB+'],
            canReceiveFrom: ['B+', 'B-', 'O+', 'O-']
        },
        'B-': {
            canDoneTo: ['B+', 'B-', 'AB+', 'AB-'],
            canReceiveFrom: ['B-', 'O-']
        },
        'AB+': {
            canDoneTo: ['AB+'],
            canReceiveFrom: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']
        },
        'AB-': {
            canDoneTo: ['AB+', 'AB-'],
            canReceiveFrom: ['A-', 'B-', 'AB-', 'O-']
        },
        'O+': {
            canDoneTo: ['A+', 'B+', 'AB+', 'O+'],
            canReceiveFrom: ['O+', 'O-']
        },
        'O-': {
            canDoneTo: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            canReceiveFrom: ['O-']
        }
    };

    if (compatibility[bloodType]) {
        var info = compatibility[bloodType];
        console.log('Blood Type: ' + bloodType);
        console.log('Can donate to: ' + info.canDoneTo.join(', '));
        console.log('Can receive from: ' + info.canReceiveFrom.join(', '));
    }
}

// Confirm dialog for important actions
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Show loading state
function showLoading(element) {
    var originalText = element.innerHTML;
    element.innerHTML = '<span class="loading"></span> Processing...';
    element.disabled = true;
    
    return function() {
        element.innerHTML = originalText;
        element.disabled = false;
    };
}

// Format date for display
function formatDate(dateString) {
    var options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Calculate age from date of birth
function calculateAge(dateOfBirth) {
    var today = new Date();
    var birthDate = new Date(dateOfBirth);
    var age = today.getFullYear() - birthDate.getFullYear();
    var monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    return age;
}

// Validate donation eligibility
function validateDonationEligibility(dateOfBirth, weight, lastDonationDate) {
    var age = calculateAge(dateOfBirth);
    var errors = [];

    // Age check
    if (age < 18) {
        errors.push('Must be at least 18 years old');
    } else if (age > 65) {
        errors.push('Must be 65 years or younger');
    }

    // Weight check
    if (weight < 50) {
        errors.push('Must weigh at least 50 kg');
    }

    // Last donation check
    if (lastDonationDate) {
        var lastDonation = new Date(lastDonationDate);
        var daysSinceLastDonation = Math.floor((new Date() - lastDonation) / (1000 * 60 * 60 * 24));
        
        if (daysSinceLastDonation < 56) {
            var nextEligibleDate = new Date(lastDonation);
            nextEligibleDate.setDate(nextEligibleDate.getDate() + 56);
            errors.push('Next eligible donation date: ' + formatDate(nextEligibleDate));
        }
    }

    return {
        eligible: errors.length === 0,
        errors: errors
    };
}

// Copy text to clipboard
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        var textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Copied to clipboard!', 'success');
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    var toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '11';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    var toastElement = document.createElement('div');
    toastElement.className = 'toast';
    toastElement.innerHTML = `
        <div class="toast-header">
            <i class="fas fa-info-circle text-${type} me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    toastContainer.appendChild(toastElement);

    // Initialize and show toast
    var toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Print functionality
function printPage() {
    window.print();
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    var table = document.getElementById(tableId);
    if (!table) return;

    var csv = [];
    var rows = table.querySelectorAll('tr');

    for (var i = 0; i < rows.length; i++) {
        var row = [];
        var cols = rows[i].querySelectorAll('td, th');

        for (var j = 0; j < cols.length; j++) {
            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }

        csv.push(row.join(','));
    }

    var csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    var downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Search functionality with highlighting
function searchAndHighlight(searchTerm, containerId) {
    var container = document.getElementById(containerId);
    if (!container) return;

    // Remove previous highlights
    var highlighted = container.querySelectorAll('.highlight');
    highlighted.forEach(function(element) {
        element.outerHTML = element.innerHTML;
    });

    if (!searchTerm) return;

    // Create regex for case-insensitive search
    var regex = new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');

    // Find and highlight matches
    var walker = document.createTreeWalker(
        container,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );

    var textNodes = [];
    var node;

    while (node = walker.nextNode()) {
        textNodes.push(node);
    }

    textNodes.forEach(function(textNode) {
        if (regex.test(textNode.textContent)) {
            var highlightedHTML = textNode.textContent.replace(regex, '<span class="highlight bg-warning">$1</span>');
            var wrapper = document.createElement('div');
            wrapper.innerHTML = highlightedHTML;
            
            while (wrapper.firstChild) {
                textNode.parentNode.insertBefore(wrapper.firstChild, textNode);
            }
            textNode.parentNode.removeChild(textNode);
        }
    });
}

// Auto-refresh functionality for real-time updates
function startAutoRefresh(intervalMinutes = 5) {
    setInterval(function() {
        // Only refresh if page is visible
        if (!document.hidden) {
            location.reload();
        }
    }, intervalMinutes * 60 * 1000);
}

// Geolocation for finding nearby donors
function getCurrentLocation() {
    return new Promise(function(resolve, reject) {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation is not supported by this browser.'));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                resolve({
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                });
            },
            function(error) {
                reject(error);
            }
        );
    });
}

// Initialize PWA functionality
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('ServiceWorker registration successful');
        }, function(err) {
            console.log('ServiceWorker registration failed');
        });
    });
}