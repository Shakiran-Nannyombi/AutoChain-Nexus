@extends('layouts.dashboard')

@section('title', 'Chat')

@section('sidebar-content')
    @include('dashboards.supplier.sidebar')
@endsection

@section('content')
<h1 class="page-title">Chat with Manufacturers</h1>

<div class="chat-container">
    <div class="chat-sidebar">
        <div class="chat-header">
            <h3><i class="fas fa-comments"></i> Conversations</h3>
        </div>
        <div class="chat-contacts">
            <div class="contact-item active">
                <div class="contact-avatar">AM</div>
                <div class="contact-info">
                    <div class="contact-name">ABC Manufacturing</div>
                    <div class="contact-last-message">Latest delivery confirmed</div>
                </div>
                <div class="contact-status online"></div>
            </div>
            <div class="contact-item">
                <div class="contact-avatar">XI</div>
                <div class="contact-info">
                    <div class="contact-name">XYZ Industries</div>
                    <div class="contact-last-message">Need urgent supplies</div>
                </div>
                <div class="contact-status online"></div>
            </div>
            <div class="contact-item">
                <div class="contact-avatar">TC</div>
                <div class="contact-info">
                    <div class="contact-name">TechCorp Ltd</div>
                    <div class="contact-last-message">Order status inquiry</div>
                </div>
                <div class="contact-status offline"></div>
            </div>
        </div>
    </div>

    <div class="chat-main">
        <div class="chat-header">
            <div class="chat-contact-info">
                <div class="contact-avatar">AM</div>
                <div class="contact-details">
                    <div class="contact-name">ABC Manufacturing</div>
                    <div class="contact-status-text">Online</div>
                </div>
            </div>
            <div class="chat-actions">
                <button class="btn-action" title="Video Call"><i class="fas fa-video"></i></button>
                <button class="btn-action" title="Voice Call"><i class="fas fa-phone"></i></button>
                <button class="btn-action" title="More"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </div>

        <div class="chat-messages">
            <div class="message-date">Today</div>
            
            <div class="message received">
                <div class="message-content">
                    <p>Hi! We need to discuss the steel sheets delivery for next week.</p>
                    <div class="message-time">10:30 AM</div>
                </div>
            </div>

            <div class="message sent">
                <div class="message-content">
                    <p>Sure! We have 500kg available. What's your requirement?</p>
                    <div class="message-time">10:32 AM</div>
                </div>
            </div>

            <div class="message received">
                <div class="message-content">
                    <p>We need 300kg by Friday. Can you confirm the delivery?</p>
                    <div class="message-time">10:35 AM</div>
                </div>
            </div>

            <div class="message sent">
                <div class="message-content">
                    <p>Absolutely! I'll schedule the delivery for Friday morning. Latest delivery confirmed.</p>
                    <div class="message-time">10:37 AM</div>
                </div>
            </div>
        </div>

        <div class="chat-input">
            <div class="input-actions">
                <button class="btn-action" title="Attach File"><i class="fas fa-paperclip"></i></button>
                <button class="btn-action" title="Emoji"><i class="fas fa-smile"></i></button>
            </div>
            <input type="text" placeholder="Type your message..." class="message-input">
            <button class="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 200px);
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .chat-sidebar {
        width: 300px;
        border-right: 1px solid #eee;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
    }

    .chat-header h3 {
        margin: 0;
        color: #333;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chat-contacts {
        flex: 1;
        overflow-y: auto;
    }

    .contact-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .contact-item:hover {
        background: #f8f9fa;
    }

    .contact-item.active {
        background: #e3f2fd;
    }

    .contact-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 0.75rem;
    }

    .contact-info {
        flex: 1;
    }

    .contact-name {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .contact-last-message {
        font-size: 0.85rem;
        color: #666;
    }

    .contact-status {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-left: 0.5rem;
    }

    .contact-status.online {
        background: #28a745;
    }

    .contact-status.offline {
        background: #6c757d;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-contact-info {
        display: flex;
        align-items: center;
    }

    .contact-details {
        margin-left: 0.75rem;
    }

    .contact-status-text {
        font-size: 0.85rem;
        color: #28a745;
    }

    .chat-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #666;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .btn-action:hover {
        background: #f0f0f0;
        color: #333;
    }

    .chat-messages {
        flex: 1;
        padding: 1rem;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .message-date {
        text-align: center;
        color: #666;
        font-size: 0.85rem;
        margin: 1rem 0;
    }

    .message {
        margin-bottom: 1rem;
        display: flex;
    }

    .message.received {
        justify-content: flex-start;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 70%;
        padding: 0.75rem 1rem;
        border-radius: 18px;
        position: relative;
    }

    .message.received .message-content {
        background: white;
        color: #333;
    }

    .message.sent .message-content {
        background: #007bff;
        color: white;
    }

    .message-content p {
        margin: 0 0 0.5rem 0;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .chat-input {
        padding: 1rem;
        border-top: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-actions {
        display: flex;
        gap: 0.25rem;
    }

    .message-input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
        font-size: 0.9rem;
    }

    .message-input:focus {
        border-color: #007bff;
    }

    .send-button {
        width: 40px;
        height: 40px;
        border: none;
        background: #007bff;
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .send-button:hover {
        background: #0056b3;
    }
</style>
@endpush 