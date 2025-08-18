<style>
.custom-btn {
    display: inline-block;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 4px;
    transition: all 0.3s ease;
    text-decoration: none;
    border: 2px solid transparent;
}

.custom-btn:hover {
    background-color: transparent !important;
    border-color: inherit;
    color: {{ $buttonTextColor ?? '#000' }} !important;
    text-decoration: none;
}

.slide-title {
    margin-bottom: 20px;
}

.slide-small-title {
    font-size: 1.2rem;
    display: block;
    margin-bottom: 10px;
}

.service-img {
    max-width: 65px;
    max-height: 65px;
    object-fit: cover;
    border-radius: 50%;
}

.column-image {
    max-height: 80vh;
}
</style>
