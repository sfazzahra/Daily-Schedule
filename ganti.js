const previewImg = document.getElementById('previewImg');
const profileImage = document.getElementById('profileImage');

profileImage.addEventListener('change', () => {
    const file = profileImage.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});