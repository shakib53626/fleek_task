<script setup>
import { onMounted, ref } from "vue";
import Pusher from "pusher-js";

const notifications = ref([]);

onMounted(() => {
    Pusher.logToConsole = true;

    const pusher = new Pusher('6ca34b8e8d5c6539e6e2', {
    cluster: 'ap2',
    encrypted: true
});

    const channel = pusher.subscribe("notifications");
    channel.bind("new-notification", function (data) {
        notifications.value.push(data.message);
    });
});
</script>

<template>
    <div>
        <h2>When you add a Product Show Notification</h2>
        <ul>
            <li v-for="(notification, index) in notifications" :key="index">
                {{ notification }}
            </li>
        </ul>
    </div>
</template>
