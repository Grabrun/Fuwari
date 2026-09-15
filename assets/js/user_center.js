document.addEventListener('DOMContentLoaded', function() {

    const profileForm = document.getElementById('profileUpdateForm');
    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('#profileUpdateButton');
            const originalButtonText = submitButton.innerHTML;
            
            // 表单验证
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                保存中...
            `;

            const formData = new FormData();
            formData.append('action', 'update_user_profile');
            formData.append('display_name', document.getElementById('display_name').value);
            formData.append('user_url', document.getElementById('user_url').value);
            formData.append('description', document.getElementById('user_description').value);
            formData.append('nonce', ajax_object.nonce);

            try {
                const response = await fetch(ajax_object.ajaxurl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.data.message);
                } else {
                    showToast(data.data.message, false);
                }
            } catch (error) {
                showToast('保存失败，请重试', false);
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }

    // 密码更新表单处理
    const passwordForm = document.getElementById('passwordUpdateForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('#passwordUpdateButton');
            const originalButtonText = submitButton.innerHTML;
            
            // 表单验证
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            // 获取密码输入
            const oldPassword = document.getElementById('securityOldPasswordInput').value;
            const newPassword = document.getElementById('securityNewPasswordInput').value;
            const confirmPassword = document.getElementById('securityConfirmPasswordInput').value;

            // 验证新密码匹配
            if (newPassword !== confirmPassword) {
                showToast('新密码与确认密码不匹配', false);
                return;
            }

            // 验证新密码长度
            if (newPassword.length < 6) {
                showToast('新密码长度至少需要6个字符', false);
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                保存中...
            `;

            const formData = new FormData();
            formData.append('action', 'update_user_password');
            formData.append('old_password', oldPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);
            formData.append('nonce', ajax_object.nonce);

            try {
                const response = await fetch(ajax_object.ajaxurl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.data.message);
                    this.reset();
                    this.classList.remove('was-validated');
                } else {
                    showToast(data.data.message, false);
                }
            } catch (error) {
                showToast('保存失败，请重试', false);
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }

    // 头像上传处理
    const avatarForm = document.getElementById('avatarForm');
    const avatarInput = document.getElementById('avatarInput');
    const uploadButton = document.getElementById('uploadAvatarButton');

    if (avatarForm && avatarInput && uploadButton) {
        uploadButton.addEventListener('click', (e) => {
            e.preventDefault();
            avatarInput.click();
        });

        avatarInput.addEventListener('change', async (e) => {
            if (!e.target.files.length) return;

            const file = e.target.files[0];
            if (file.size > 1024 * 1024) {
                showToast('文件大小不能超过1MB', false);
                return;
            }

            const originalButtonText = uploadButton.innerHTML;
            uploadButton.disabled = true;
            uploadButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                上传中...
            `;

            const formData = new FormData();
            formData.append('action', 'upload_avatar');
            formData.append('avatar', file);
            formData.append('nonce', ajax_object.nonce);

            try {
                const response = await fetch(ajax_object.ajaxurl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.data.message);
                    // 可以在这里添加刷新头像显示的代码
                    location.reload(); // 或者更新特定的头像元素
                } else {
                    showToast(data.data.message, false);
                }
            } catch (error) {
                showToast('上传失败，请重试', false);
            } finally {
                uploadButton.disabled = false;
                uploadButton.innerHTML = originalButtonText;
                avatarInput.value = '';
            }
        });
    }

    // 处理删除收藏
    const deleteFavoriteButtons = document.querySelectorAll('.delete-favorite');
    if (deleteFavoriteButtons.length) {
        // 添加模态框到页面
        document.body.insertAdjacentHTML('beforeend', `
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">确认取消收藏</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>确定要取消这个收藏吗？</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">确定删除</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        let currentPostId = null;
        let currentRow = null;

        deleteFavoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentPostId = this.dataset.postId;
                currentRow = this.closest('tr');
                confirmModal.show();
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', async function() {
            confirmModal.hide();
            
            const formData = new FormData();
            formData.append('action', 'delete_favorite');
            formData.append('post_id', currentPostId);
            formData.append('nonce', ajax_object.nonce);

            try {
                const response = await fetch(ajax_object.ajaxurl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.data.message);
                    currentRow.remove();
                                        const tbody = document.querySelector('tbody');
                    if (tbody.querySelectorAll('tr').length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center">
                                    <span>没有收藏</span>
                                </td>
                            </tr>
                        `;
                    }
                } else {
                    showToast(data.data.message, false);
                }
            } catch (error) {
                showToast('操作失败，请重试', false);
            }
        });
    }


});
