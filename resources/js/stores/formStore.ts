import { defineStore } from 'pinia';
import { ref } from 'vue';

// 1. Convert the store definition into a factory function
export const createFormStore = (storeType: string) => {
    // 2. Use the storeType to dynamically generate a unique Pinia store ID
    return defineStore(`formStore:${storeType}`, () => {
        const storedData = ref<Record<string, string>>({});
        
        function storeFormDataItem(key: string, value: string) {
            storedData.value[key] = value;
        }

        function getFormDataItem(key: string) {
            return storedData.value[key];
        }

        function getAllData() {
            return storedData;
        }

        return { storeFormDataItem, getFormDataItem, storedData, getAllData };
    });
};
 