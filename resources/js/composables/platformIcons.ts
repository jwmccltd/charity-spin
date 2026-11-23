import { computed, ref, watch, toValue, type MaybeRefOrGetter } from 'vue';

export type PlatformName =
  | 'twitter'
  | 'instagram'
  | 'linkedin'
  | 'github'
  | 'youtube'
  | 'tiktok'
  | 'globe';

export type IconResult =
  | { type: 'svg'; name: PlatformName }
  | { type: 'image'; src: string };

const PLATFORM_MAP: Record<string, Exclude<PlatformName, 'globe'>> = {
  'twitter.com': 'twitter',
  'x.com': 'twitter',
  'instagram.com': 'instagram',
  'linkedin.com': 'linkedin',
  'github.com': 'github',
  'youtube.com': 'youtube',
  'youtu.be': 'youtube',
  'tiktok.com': 'tiktok',
};

export function usePlatformIcon(rawUrl: MaybeRefOrGetter<string>) {
  const imageFailed = ref(false);

  // Reset error state whenever the user types or pastes a new URL
  watch(() => toValue(rawUrl), () => {
    imageFailed.value = false;
  });

  const icon = computed<IconResult>(() => {
    const trimmed = toValue(rawUrl).trim();
    if (!trimmed) {
      return { type: 'svg', name: 'globe' };
    }

    const normalizedUrl = /^https?:\/\//i.test(trimmed)
      ? trimmed
      : `https://${trimmed}`;

    try {
      const hostname = new URL(normalizedUrl).hostname
        .toLowerCase()
        .replace(/^www\./, '');

      const matchedKey = Object.keys(PLATFORM_MAP).find(
        (domain) => hostname === domain || hostname.endsWith(`.${domain}`)
      );

      if (matchedKey) {
        return { type: 'svg', name: PLATFORM_MAP[matchedKey] };
      }

      // Fall back to a generic globe icon if the favicon fails to load
      if (imageFailed.value || !hostname.includes('.')) {
        return { type: 'svg', name: 'globe' };
      }

      return {
        type: 'image',
        src: `https://www.google.com/s2/favicons?domain=${encodeURIComponent(hostname)}&sz=128`,
      };
    } catch {
      return { type: 'svg', name: 'globe' };
    }
  });

  function onImageError() {
    imageFailed.value = true;
  }

  return {
    icon,
    onImageError,
  };
}
