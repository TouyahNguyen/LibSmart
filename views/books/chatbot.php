<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card" style="max-width: 600px; margin: 40px auto;">
    <div class="content-header" style="justify-content: flex-start;">
        <h2 style="margin-bottom: 0;">LibSmart Chatbot AI</h2>
        <a href="index.php?action=home" class="btn" style="background-color: #ccc; margin-left: auto;">Quay lại Trang chủ</a>
    </div>
    <div id="chat-window" style="background: #f8f8f8; border-radius: 8px; padding: 20px; min-height: 300px; margin-bottom: 20px; overflow-y: auto; max-height: 400px; font-size: 16px;"></div>
    <form id="chat-form" style="display: flex; gap: 10px; margin-bottom: 10px;">
        <input type="text" id="chat-input" class="form-control" placeholder="Nhập câu hỏi về sách..." style="flex:1;">
        <button type="submit" class="btn btn-primary">Gửi</button>
    </form>
    <p style="color: #888; font-size: 15px; margin-top: 10px;">Chatbot AI hỗ trợ tra cứu thông tin sách, tác giả, danh mục, hướng dẫn sử dụng thư viện.</p>
</div>
<script>
const chatWindow = document.getElementById('chat-window');
const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');
function appendMessage(sender, text) {
    const msg = document.createElement('div');
    msg.innerHTML = `<b>${sender}:</b> <span style='white-space: pre-line; display: inline-block; max-width: 90%; line-height: 1.7;'>${text}</span>`;
    msg.style.marginBottom = '18px';
    msg.style.padding = '8px 14px';
    msg.style.borderRadius = '8px';
    msg.style.background = sender === 'AI' ? '#eaf6ff' : '#f3f3f3';
    msg.style.wordBreak = 'break-word';
    chatWindow.appendChild(msg);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}
let loadingMsg = null;
chatForm.onsubmit = async function(e) {
    e.preventDefault();
    const question = chatInput.value.trim();
    if (!question) return;
    appendMessage('Bạn', question);
    chatInput.value = '';
    loadingMsg = document.createElement('div');
    loadingMsg.innerHTML = `<b>AI:</b> <span class="chatbot-loading"><i>Đang trả lời...</i></span>`;
    loadingMsg.style.marginBottom = '10px';
    chatWindow.appendChild(loadingMsg);
    chatWindow.scrollTop = chatWindow.scrollHeight;
    try {
        const res = await fetch('index.php?action=chatbot_api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question })
        });
        const data = await res.json();
        console.log('Chatbot response:', data);
        if (data.answer) {
            loadingMsg.innerHTML = `<b>AI:</b> ${data.answer}`;
        } else {
            loadingMsg.innerHTML = `<b>AI:</b> <span style='color:red'>Lỗi dữ liệu: ${JSON.stringify(data)}</span>`;
        }
    } catch (err) {
        console.log('Chatbot fetch error:', err);
        loadingMsg.innerHTML = `<b>AI:</b> <span style='color:red'>Lỗi kết nối đến máy chủ!</span>`;
    }
};
</script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
