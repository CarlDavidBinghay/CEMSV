<section id="logout-section" class="fixed inset-0 bg-white flex flex-col items-center justify-center p-6 z-50">
    <i class="ri-logout-box-line text-6xl md:text-8xl text-red-500 mb-6"></i>
    <h1 class="text-3xl md:text-5xl font-semibold text-gray-800 mb-4 text-center">Logged Out</h1>
    <p class="text-center text-gray-600 mb-6">You have successfully logged out.</p>
    <a href="login.php" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition duration-200">
        Go to Login
    </a>
</section>

<script>
// Auto-redirect after 3 seconds
setTimeout(() => {
    window.location.href = 'landing.php';
}, 3000);
</script>