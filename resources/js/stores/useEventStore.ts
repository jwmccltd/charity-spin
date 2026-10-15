import { defineStore } from 'pinia';
import { ref } from 'vue';

// 1. Define the interface for the events your app supports
export interface AppEvents {
  'user-logged-in': { username: string; role: string };
  'toast-notification': { message: string; type: 'success' | 'error' };
  'cart-updated': { itemCount: number };
}

// 2. Shape of the stored event object
export interface EventPayload<K extends keyof AppEvents> {
  name: K;
  payload: AppEvents[K];
  timestamp: number;
}

export const useEventStore = defineStore('eventStore', () => {
  // Use a generic type or unknown initially for the reactive state
  const lastEvent = ref<EventPayload<keyof AppEvents> | null>(null);

  // 3. Strongly typed action
  function triggerEvent<K extends keyof AppEvents>(eventName: K, payload: AppEvents[K]): void {
    lastEvent.value = {
      name: eventName,
      payload: payload,
      timestamp: Date.now(),
    };

    console.log(`[Global Event]: ${eventName}`, payload);
  }

  return { lastEvent, triggerEvent };
});
