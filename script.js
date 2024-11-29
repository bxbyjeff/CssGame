document.getElementById('run-css').addEventListener('click', () => {
    const cssCode = document.getElementById('css-code').value;
    const previewBox = document.getElementById('preview-box');
    
    // ล้างสไตล์เก่า
    previewBox.style = "";

    // เพิ่มสไตล์ใหม่
    try {
        previewBox.style.cssText = cssCode;
    } catch (error) {
        alert("คำสั่ง CSS ไม่ถูกต้อง!");
    }
});
