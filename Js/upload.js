const dropArea = document.getElementById('drop-area');
const input = document.getElementById('photo');
const dropText = document.getElementById('drop-text');
const preview = document.getElementById('preview');
const MAX_SIZE = 5 * 1024 * 1024;

function validateFile(file) {
    const validTypes = ['image/jpeg', 'image/png'];
    if (!validTypes.includes(file.type)) {
        dropText.textContent = "Only JPEG and PNG images are allowed.";
        preview.style.display = "none";
        return false;
    }
    if (file.size > MAX_SIZE) {
        dropText.textContent = "File must be less than 5MB.";
        preview.style.display = "none";
        return false;
    }
    return true;
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = "block";
        document.getElementById('instructions').style.display = "none";
    };
    reader.readAsDataURL(file);
}

function setFileToInput(file) {
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    input.files = dataTransfer.files;
}

dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('dragover'); 
    dropText.textContent = "Drop your file here";
});

dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('dragover');
});

dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('dragover');

    if (e.dataTransfer.files.length) {
        const file = e.dataTransfer.files[0];
        if (validateFile(file)) {
            dropText.textContent = file.name;
            showPreview(file);
            setFileToInput(file); 
        }
    }
});

input.addEventListener('change', () => {
    if (input.files.length) {
        const file = input.files[0];
        if (validateFile(file)) {
            dropText.textContent = file.name;
            showPreview(file);
        } else {
            preview.style.display = "none";
            document.getElementById('instructions').style.display = "block";
        }
    }
});

document.querySelector('form').addEventListener('submit', function (e) {
    const file = input.files[0];
    if (!file || !validateFile(file)) {
        e.preventDefault(); 
        dropText.textContent = "Please select a valid JPEG or PNG file under 5MB.";
    }
});
