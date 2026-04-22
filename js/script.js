
function toggleMobileMenu() {
    
    const mobileNav = document.getElementById('mobileNav');
    
    
    
    mobileNav.classList.toggle('show');
}



document.addEventListener('DOMContentLoaded', function() {
    const mobileNavLinks = document.querySelectorAll('.mobile-nav a');
    
    mobileNavLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            const mobileNav = document.getElementById('mobileNav');
            mobileNav.classList.remove('show');
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    
    const starContainers = document.querySelectorAll('.star-rating-input');
    
    starContainers.forEach(function(container) {
        
        const stars = container.querySelectorAll('label');
        const inputs = container.querySelectorAll('input');
        
        
        stars.forEach(function(star, index) {
            star.addEventListener('mouseenter', function() {
                
                highlightStars(stars, 5 - index);
            });
            
            star.addEventListener('mouseleave', function() {
                
                const selectedInput = container.querySelector('input:checked');
                if (selectedInput) {
                    highlightStars(stars, selectedInput.value);
                } else {
                    highlightStars(stars, 0);
                }
            });
        });
    });
});


function highlightStars(stars, count) {
    stars.forEach(function (star, index) {
        
        
        const starValue = 5 - index;
        
        if (starValue <= count) {
            star.style.color = '#f59e0b';
        } else {
            star.style.color = '#d1d5db';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    
        const forms = document.querySelectorAll('.validate-form');
    
        forms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                let isValid = true;
                let firstError = null;
            
            
                const requiredFields = form.querySelectorAll('[required]');
            
                requiredFields.forEach(function (field) {
                
                    field.classList.remove('error');
                
                
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                    
                    
                        if (!firstError) {
                            firstError = field;
                        }
                    }
                });
            
            
                const emailField = form.querySelector('input[type="email"]');
                if (emailField && emailField.value.trim()) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailField.value)) {
                        isValid = false;
                        emailField.classList.add('error');
                        if (!firstError) {
                            firstError = emailField;
                        }
                    }
                }
            
            
                const phoneField = form.querySelector('input[type="tel"]');
                if (phoneField && phoneField.value.trim()) {
                
                    const phonePattern = /^[\d\s\-\(\)\+]+$/;
                    if (!phonePattern.test(phoneField.value)) {
                        isValid = false;
                        phoneField.classList.add('error');
                        if (!firstError) {
                            firstError = phoneField;
                        }
                    }
                }
            
            
                if (!isValid) {
                    event.preventDefault();
                
                
                    if (firstError) {
                        firstError.focus();
                    }
                
                
                    showNotification('Please fill in all required fields correctly.', 'error');
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('lawyerSearch');
    
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const lawyerCards = document.querySelectorAll('.lawyer-card');
            
                lawyerCards.forEach(function (card) {
                    const name = card.querySelector('h3').textContent.toLowerCase();
                    const specialization = card.querySelector('.lawyer-specialization').textContent.toLowerCase();
                
                
                    if (name.includes(searchTerm) || specialization.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
    function confirmDelete(message) {
    
    
        return confirm(message || 'Are you sure you want to delete this item?');
    }


    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
    
        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                const message = this.dataset.confirmMessage || 'Are you sure you want to delete this?';
            
                if (!confirmDelete(message)) {
                
                    event.preventDefault();
                }
            });
        });
    });

    function showNotification(message, type) {
    
        const notification = document.createElement('div');
        notification.className = 'notification notification-' + type;
        notification.textContent = message;
    
    
        notification.style.cssText = `
        position: fixed;
        top: 90px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: opacity 0.3s ease;
    `;
    
    
        if (type === 'success') {
            notification.style.backgroundColor = '#10b981';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#ef4444';
        } else {
            notification.style.backgroundColor = '#3b82f6';
        }
    
    
        document.body.appendChild(notification);
    
    
        setTimeout(function () {
            notification.style.opacity = '0';
            setTimeout(function () {
                notification.remove();
            }, 300);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
        anchorLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                const targetId = this.getAttribute('href');
            
                if (targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                
                    if (targetElement) {
                        event.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
    
        forms.forEach(function (form) {
            form.addEventListener('submit', function () {
                const submitButton = form.querySelector('button[type="submit"]');
            
                if (submitButton) {
                
                    const originalText = submitButton.textContent;
                    submitButton.dataset.originalText = originalText;
                
                
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                }
            });
        });
    });

