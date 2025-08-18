<!-- Notification Container -->
<div id="notification-container">

</div>


@push('styles')
    <!-- Notification CSS -->
    <style>
        #notification-container {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .notification {
            position: absolute;
            pointer-events: auto;
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            transition: all 0.3s ease;
            max-width: 350px;
            opacity: 0;
            transform: translateY(-20px);
            overflow: hidden;
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification-success {
            background-color: #28a745;
            color: white;
        }

        .notification-error {
            background-color: #dc3545;
            color: white;
        }

        .notification-general {
            background-color: #17a2b8;
            color: white;
        }

        .notification-warning {
            background-color: #ffc107;
            color: white;
        }

        .notification-content {
            flex: 1;
            margin-right: 10px;
        }

        .notification-icon {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .notification-close {
            cursor: pointer;
            border: none;
            background: transparent;
            color: inherit;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: opacity 0.2s;
            padding: 0;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .notification-close:hover {
            opacity: 1;
        }

        /* Position Classes */
        .notification-position-top {
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
        }

        .notification-position-top.show {
            transform: translateX(-50%) translateY(0);
        }

        .notification-position-topLeft {
            top: 20px;
            left: 20px;
        }

        .notification-position-topRight {
            top: 20px;
            right: 20px;
        }

        .notification-position-bottom {
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
        }

        .notification-position-bottom.show {
            transform: translateX(-50%) translateY(0);
        }

        .notification-position-bottomLeft {
            bottom: 20px;
            left: 20px;
            transform: translateY(20px);
        }

        .notification-position-bottomLeft.show {
            transform: translateY(0);
        }

        .notification-position-bottomRight {
            bottom: 20px;
            right: 20px;
            transform: translateY(20px);
        }

        .notification-position-bottomRight.show {
            transform: translateY(0);
        }

        /* Progress Bar */
        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.7);
            width: 100%;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform linear;
        }
    </style>
@endpush
@push('scripts')
    <!-- Notification JavaScript -->
    <script>
        // Make sure jQuery is available
        (function(callback) {
            if (window.jQuery) {
                // jQuery is already loaded, initialize immediately
                callback(window.jQuery);
            } else {
                // Check if jQuery is being loaded elsewhere
                var checkJQuery = setInterval(function() {
                    if (window.jQuery) {
                        clearInterval(checkJQuery);
                        callback(window.jQuery);
                    }
                }, 100);

                // If after 2 seconds jQuery still isn't available, load it
                setTimeout(function() {
                    if (!window.jQuery) {
                        clearInterval(checkJQuery);
                        var script = document.createElement('script');
                        script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                        script.integrity = 'sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=';
                        script.crossOrigin = 'anonymous';
                        script.onload = function() {
                            callback(window.jQuery);
                        };
                        document.head.appendChild(script);
                    }
                }, 2000);
            }
        })(function($) {
            // This is the callback that runs once jQuery is available
            $(document).ready(function() {
                // Create notification counter to ensure unique IDs
                window.notificationCounter = window.notificationCounter || 0;

                /**
                 * Display a notification
                 * @param {string} type - Type of notification: 'success', 'error', 'general'
                 * @param {string} message - Message to display
                 * @param {string} position - Position of notification: 'top', 'topRight', etc.
                 * @param {number} duration - Duration in seconds before auto-close
                 */
                window.displayNotification = function(type, message, position = 'topRight', duration = 5) {
                    // Check required parameters
                    if (!type || !message) {
                        console.error('Notification requires type and message parameters');
                        return;
                    }

                    // Validate type
                    const validTypes = ['success', 'error', 'general'];
                    if (!validTypes.includes(type)) {
                        console.error('Invalid notification type. Use: success, error, or general');
                        type = 'general';
                    }

                    // Validate position
                    const validPositions = ['top', 'topRight', 'topLeft', 'bottom', 'bottomRight',
                        'bottomLeft'
                    ];
                    if (!validPositions.includes(position)) {
                        console.error('Invalid position. Defaulting to topRight');
                        position = 'topRight';
                    }

                    // Generate a unique ID for this notification
                    const notificationId = 'notification-' + (++window.notificationCounter);

                    // Set icon based on type
                    let icon;
                    switch (type) {
                        case 'success':
                            icon = '<i class="fas fa-check-circle"></i>';
                            break;
                        case 'error':
                            icon = '<i class="fas fa-exclamation-circle"></i>';
                            break;
                        default:
                            icon = '<i class="fas fa-info-circle"></i>';
                    }

                    // Create notification HTML
                    const notificationHtml = `
                    <div id="${notificationId}" class="notification notification-${type} notification-position-${position}">
                        <div class="notification-icon">${icon}</div>
                        <div class="notification-content">${message}</div>
                        <button type="button" class="notification-close" aria-label="Close">&times;</button>
                        <div class="notification-progress"></div>
                    </div>
                `;

                    // Add to container
                    $('#notification-container').append(notificationHtml);

                    // Show notification with animation
                    const $notification = $(`#${notificationId}`);

                    // Position multiple notifications in stack if needed
                    const positionClass = `notification-position-${position}`;
                    const existingNotifications = $(`.${positionClass}`).not($notification);
                    let offsetY = 0;

                    existingNotifications.each(function() {
                        offsetY += $(this).outerHeight(true);
                    });

                    // Apply offset for stacking if needed
                    if (offsetY > 0) {
                        if (position.includes('top')) {
                            $notification.css('top', (20 + offsetY) + 'px');
                        } else if (position.includes('bottom')) {
                            $notification.css('bottom', (20 + offsetY) + 'px');
                        }
                    }

                    // Handle progress bar animation
                    if (duration > 0) {
                        const $progress = $notification.find('.notification-progress');

                        // Show notification
                        setTimeout(() => {
                            $notification.addClass('show');

                            // Animate progress bar
                            $progress.css({
                                'transform': 'scaleX(1)',
                                'transition-duration': `${duration}s`
                            });
                        }, 10);

                        // Auto close after duration
                        setTimeout(() => {
                            closeNotification($notification);
                        }, duration * 1000);
                    } else {
                        // If no duration (0 or negative), just show notification without progress bar
                        setTimeout(() => {
                            $notification.addClass('show');
                        }, 10);
                    }

                    // Close button handler
                    $notification.find('.notification-close').on('click', function() {
                        closeNotification($notification);
                    });

                    // Return the notification element
                    return $notification;
                };

                // Helper function to close notifications
                function closeNotification($notification) {
                    if (!$notification.hasClass('closing')) {
                        $notification.addClass('closing');

                        // Start closing animation
                        $notification.css({
                            'opacity': '0',
                            'transform': $notification.hasClass('notification-position-top') ?
                                'translateX(-50%) translateY(-20px)' : ($notification.hasClass(
                                        'notification-position-bottom') ?
                                    'translateX(-50%) translateY(20px)' :
                                    'translateY(20px)')
                        });

                        // Remove from DOM after animation
                        setTimeout(() => {
                            const position = $notification.attr('class').match(
                                /notification-position-(\w+)/)[1];
                            const positionClass = `notification-position-${position}`;

                            // Remove the notification
                            $notification.remove();

                            // Adjust positions of remaining notifications
                            rearrangeNotifications(positionClass);
                        }, 300);
                    }
                }

                // Rearrange remaining notifications after one is closed
                function rearrangeNotifications(positionClass) {
                    const $notifications = $(`.${positionClass}`);
                    let offsetY = 0;

                    $notifications.each(function() {
                        const $this = $(this);
                        if (positionClass.includes('top')) {
                            $this.css('top', (20 + offsetY) + 'px');
                        } else if (positionClass.includes('bottom')) {
                            $this.css('bottom', (20 + offsetY) + 'px');
                        }
                        offsetY += $this.outerHeight(true);
                    });
                }
            });
        });
    </script>
@endpush
