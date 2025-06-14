/**
 * LAB 6 - ADVANCED SECURITY & API JAVASCRIPT
 * Interactive functionality for security testing, API documentation, and deployment
 */

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeSecurityTesting();
    initializeAPITesting();
    initializeDeploymentGuide();
    initializeCopyButtons();
    initializeTooltips();
    initializeAnimations();
});

/**
 * Security Testing Functionality
 */
function initializeSecurityTesting() {
    // Add real-time validation feedback
    const securityForms = document.querySelectorAll('.security-form');
    
    securityForms.forEach(form => {
        const inputs = form.querySelectorAll('input[type="text"], textarea');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateSecurityInput(this);
            });
        });
    });
    
    // CSRF token refresh
    const csrfForms = document.querySelectorAll('form[data-csrf="true"]');
    csrfForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            refreshCSRFToken(this);
        });
    });
}

function validateSecurityInput(input) {
    const value = input.value;
    const container = input.closest('.form-group');
    
    // Remove existing validation classes
    container.classList.remove('has-warning', 'has-danger');
    
    // Check for potential security issues
    if (containsSQLInjection(value)) {
        showSecurityWarning(container, 'Potential SQL injection detected');
    } else if (containsXSS(value)) {
        showSecurityWarning(container, 'Potential XSS payload detected');
    } else if (value.length > 0) {
        showSecuritySuccess(container, 'Input appears safe');
    }
}

function containsSQLInjection(input) {
    const sqlPatterns = [
        /('|(\\')|(;)|(\\;))/i,
        /((\s*(union|select|insert|delete|update|drop|create|alter|exec|execute)\s+))/i,
        /((\s*(or|and)\s+[\w\s]*\s*=\s*[\w\s]*\s*))/i
    ];
    
    return sqlPatterns.some(pattern => pattern.test(input));
}

function containsXSS(input) {
    const xssPatterns = [
        /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        /<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
        /javascript:/gi,
        /on\w+\s*=/gi
    ];
    
    return xssPatterns.some(pattern => pattern.test(input));
}

function showSecurityWarning(container, message) {
    container.classList.add('has-warning');
    updateHelpBlock(container, message, 'warning');
}

function showSecuritySuccess(container, message) {
    container.classList.add('has-success');
    updateHelpBlock(container, message, 'success');
}

function updateHelpBlock(container, message, type) {
    let helpBlock = container.querySelector('.help-block');
    if (!helpBlock) {
        helpBlock = document.createElement('div');
        helpBlock.className = 'help-block';
        container.appendChild(helpBlock);
    }
    
    helpBlock.textContent = message;
    helpBlock.className = `help-block text-security-${type}`;
}

/**
 * API Testing Functionality
 */
function initializeAPITesting() {
    const apiTesters = document.querySelectorAll('.api-tester');
    
    apiTesters.forEach(tester => {
        const form = tester.querySelector('.api-test-form');
        const responseArea = tester.querySelector('.api-test-response');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                executeAPITest(this, responseArea);
            });
        }
    });
    
    // Syntax highlighting for code blocks
    highlightCodeBlocks();
}

function executeAPITest(form, responseArea) {
    const formData = new FormData(form);
    const method = formData.get('method') || 'GET';
    const endpoint = formData.get('endpoint') || '';
    const token = formData.get('token') || '';
    
    // Show loading state
    responseArea.textContent = 'Sending request...';
    responseArea.classList.add('loading');
    
    // Prepare request options
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (token) {
        options.headers['Authorization'] = `Bearer ${token}`;
    }
    
    if (method !== 'GET' && method !== 'DELETE') {
        const body = formData.get('body');
        if (body) {
            try {
                options.body = JSON.stringify(JSON.parse(body));
            } catch (e) {
                options.body = body;
            }
        }
    }
    
    // Make the API request
    fetch(endpoint, options)
        .then(response => {
            return response.text().then(text => {
                try {
                    return {
                        status: response.status,
                        statusText: response.statusText,
                        data: JSON.parse(text)
                    };
                } catch (e) {
                    return {
                        status: response.status,
                        statusText: response.statusText,
                        data: text
                    };
                }
            });
        })
        .then(result => {
            displayAPIResponse(responseArea, result);
        })
        .catch(error => {
            displayAPIError(responseArea, error);
        })
        .finally(() => {
            responseArea.classList.remove('loading');
        });
}

function displayAPIResponse(responseArea, result) {
    const formattedResponse = {
        status: `${result.status} ${result.statusText}`,
        timestamp: new Date().toISOString(),
        response: result.data
    };
    
    responseArea.textContent = JSON.stringify(formattedResponse, null, 2);
    responseArea.classList.add('fade-in');
}

function displayAPIError(responseArea, error) {
    const errorResponse = {
        error: true,
        message: error.message,
        timestamp: new Date().toISOString()
    };
    
    responseArea.textContent = JSON.stringify(errorResponse, null, 2);
    responseArea.classList.add('fade-in');
}

function highlightCodeBlocks() {
    const codeBlocks = document.querySelectorAll('.api-code-block, pre');
    
    codeBlocks.forEach(block => {
        // Add copy button
        if (!block.querySelector('.copy-btn')) {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.textContent = 'Copy';
            copyBtn.addEventListener('click', () => copyToClipboard(block.textContent));
            block.style.position = 'relative';
            block.appendChild(copyBtn);
        }
    });
}

/**
 * Deployment Guide Functionality
 */
function initializeDeploymentGuide() {
    // Progress tracking
    initializeProgressTracking();
    
    // Network configuration detection
    detectNetworkConfiguration();
    
    // Checklist functionality
    initializeChecklists();
}

function initializeProgressTracking() {
    const steps = document.querySelectorAll('.deployment-step');
    const progressBar = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    
    if (steps.length > 0 && progressBar) {
        let completedSteps = 0;
        
        steps.forEach((step, index) => {
            const checkbox = step.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        completedSteps++;
                        step.classList.add('completed');
                    } else {
                        completedSteps--;
                        step.classList.remove('completed');
                    }
                    
                    updateProgress(completedSteps, steps.length, progressBar, progressText);
                });
            }
        });
    }
}

function updateProgress(completed, total, progressBar, progressText) {
    const percentage = (completed / total) * 100;
    progressBar.style.width = `${percentage}%`;
    
    if (progressText) {
        progressText.textContent = `${completed} of ${total} steps completed (${Math.round(percentage)}%)`;
    }
}

function detectNetworkConfiguration() {
    const configDisplay = document.querySelector('.network-config');
    
    if (configDisplay) {
        // Get user's IP (this would need a service in real implementation)
        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                updateNetworkConfig('Public IP', data.ip);
            })
            .catch(() => {
                updateNetworkConfig('Public IP', 'Unable to detect');
            });
        
        // Detect local IP (approximate)
        const localIP = getLocalIP();
        updateNetworkConfig('Local IP', localIP);
        
        // Default ports
        updateNetworkConfig('HTTP Port', '80');
        updateNetworkConfig('HTTPS Port', '443');
        updateNetworkConfig('PHP Dev Server', '8000');
    }
}

function getLocalIP() {
    // This is a simplified version - in reality, you'd need more sophisticated detection
    return '192.168.1.x';
}

function updateNetworkConfig(label, value) {
    const configDisplay = document.querySelector('.network-config');
    if (configDisplay) {
        const configItem = document.createElement('div');
        configItem.className = 'config-item';
        configItem.innerHTML = `
            <span class="config-label">${label}:</span>
            <span class="config-value">${value}</span>
        `;
        configDisplay.appendChild(configItem);
    }
}

function initializeChecklists() {
    const checklistItems = document.querySelectorAll('.checklist-item');
    
    checklistItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    item.classList.add('completed');
                } else {
                    item.classList.remove('completed');
                }
            });
        }
    });
}

/**
 * Utility Functions
 */
function initializeCopyButtons() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('copy-btn')) {
            const codeBlock = e.target.closest('pre, .api-code-block, .terminal-output');
            if (codeBlock) {
                const text = codeBlock.textContent.replace('Copy', '').trim();
                copyToClipboard(text);
                
                // Visual feedback
                e.target.textContent = 'Copied!';
                setTimeout(() => {
                    e.target.textContent = 'Copy';
                }, 2000);
            }
        }
    });
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
}

function initializeTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        const tooltipText = element.getAttribute('data-tooltip');
        
        element.addEventListener('mouseenter', function() {
            showTooltip(this, tooltipText);
        });
        
        element.addEventListener('mouseleave', function() {
            hideTooltip(this);
        });
    });
}

function showTooltip(element, text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip-popup';
    tooltip.textContent = text;
    tooltip.style.cssText = `
        position: absolute;
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        z-index: 1000;
        pointer-events: none;
    `;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    
    element._tooltip = tooltip;
}

function hideTooltip(element) {
    if (element._tooltip) {
        document.body.removeChild(element._tooltip);
        delete element._tooltip;
    }
}

function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    });
    
    // Observe elements that should animate on scroll
    const animateElements = document.querySelectorAll('.test-section, .api-endpoint, .deployment-step');
    animateElements.forEach(el => observer.observe(el));
}

function refreshCSRFToken(form) {
    // This would typically make an AJAX request to get a new CSRF token
    const tokenInput = form.querySelector('input[name="csrf_token"]');
    if (tokenInput) {
        // Placeholder - in real implementation, fetch new token from server
        console.log('Refreshing CSRF token...');
    }
}

// Export functions for global access if needed
window.Lab6 = {
    validateSecurityInput,
    executeAPITest,
    copyToClipboard,
    updateProgress
};
