@once
    <script>
        function openPrintPreview(url, openNewTab = false, closeAfterPrint = true) {
            if (openNewTab) {
                var newTab = window.open(url, '_blank');
                if (newTab) {
                    newTab.onload = function() {
                        setTimeout(function() {
                            newTab.print();
                            if (closeAfterPrint) {
                                if (typeof newTab.onafterprint !== 'undefined') {
                                    newTab.close();
                                } else {
                                    setTimeout(function() {
                                        newTab.close();
                                    }, 2000);
                                }
                            }
                        }, 500); // Adjust delay if needed for content to load
                    };
                } else {
                    alert('Please allow popups for this site.');
                }
            } else {
                var iframe = document.createElement('iframe');
                iframe.src = url;
                // iframe.style.display = 'none';
                document.body.appendChild(iframe);

                iframe.onload = function() {
                    setTimeout(function() {
                        iframe.contentWindow.print();
                    }, 1000);

                    setTimeout(function() {
                        document.body.removeChild(iframe);
                    }, 3000);
                };
            }
        }
    </script>
@endonce
