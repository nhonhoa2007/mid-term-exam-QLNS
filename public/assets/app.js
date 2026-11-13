document.querySelectorAll('[data-password-toggle]').forEach(button => {
  button.addEventListener('click', () => {
    const input = document.getElementById(button.dataset.passwordToggle);
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    button.textContent = show ? 'Ẩn' : 'Hiện';
    button.setAttribute('aria-label', show ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
  });
});
const dialog = document.getElementById('delete-dialog');
document.querySelectorAll('[data-delete-id]').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('delete-id').value = button.dataset.deleteId;
    document.getElementById('delete-email').textContent = button.dataset.deleteEmail;
    dialog.showModal();
  });
});
document.getElementById('cancel-delete')?.addEventListener('click', () => dialog.close());
dialog?.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
let previewUrl;
document.getElementById('images')?.addEventListener('change', event => {
  const file = event.target.files[0];
  const help = document.getElementById('image-help');
  if (!file) return;
  if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2097152) {
    help.textContent = 'Vui lòng chọn ảnh JPG, PNG hoặc WebP tối đa 2 MB.';
    help.className = 'field-error';
    event.target.value = '';
    return;
  }
  if (previewUrl) URL.revokeObjectURL(previewUrl);
  previewUrl = URL.createObjectURL(file);
  const image = new Image();
  image.src = previewUrl;
  image.alt = 'Xem trước ảnh đại diện';
  image.className = 'avatar avatar-large';
  document.getElementById('upload-preview').replaceChildren(image);
  help.textContent = file.name;
  help.className = '';
});
