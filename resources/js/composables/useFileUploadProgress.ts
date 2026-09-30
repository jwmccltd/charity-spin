import { ref, onUnmounted } from 'vue';
import axios from 'axios';

// Define the strict typing for the job status
export type ProgressStatus = 'pending' | 'processing' | 'completed' | 'failed';

// Define the structure of the response coming from Laravel
interface ProgressResponse {
  status: ProgressStatus;
  progress: number;
  error?: string;
}

export function useFileUploadProgress() {
  const progress = ref<number>(0);
  const status = ref<ProgressStatus>('pending');
  const errorMessage = ref<string>('');
  
  // Use ReturnType to support both Browser and Node environments correctly
  let pollInterval: ReturnType<typeof setInterval> | null = null;

  // Stop polling manually
  const stopPolling = (): void => {
    if (pollInterval) {
      clearInterval(pollInterval);
      pollInterval = null;
    }
  };

  // Kick off the polling loop
  const startPolling = (trackerKey: string): void => {
    // Reset states
    progress.value = 0;
    status.value = 'processing';
    errorMessage.value = '';
    
    stopPolling();

    pollInterval = setInterval(async () => {
      try {
        const response = await axios.get<ProgressResponse>(`/api/file-progress/${trackerKey}`);
        
        status.value = response.data.status;
        progress.value = response.data.progress;

        if (response.data.status === 'completed' || response.data.status === 'failed') {
          stopPolling();
          if (response.data.status === 'failed') {
            errorMessage.value = response.data.error || 'An unexpected error occurred.';
          }
        }
      } catch (error: any) {
        stopPolling();
        status.value = 'failed';
        errorMessage.value = error.response?.data?.message || 'Failed to fetch progress updates.';
      }
    }, 1500);
  };

  // Automatically clear the interval if the user leaves the page/component
  onUnmounted(() => {
    stopPolling();
  });

  return {
    progress,
    status,
    errorMessage,
    startPolling,
    stopPolling
  };
}
