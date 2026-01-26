// API Client for Laravel Backend
import type {
  Page,
  PageData,
  CreatePageInput,
  UpdatePageInput,
  MediaUploadResponse,
} from '@/types/visual-editor';

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8001/api';

export class ApiError extends Error {
  constructor(public status: number, message: string) {
    super(message);
    this.name = 'ApiError';
  }
}

async function fetchApi<T>(
  endpoint: string,
  options?: RequestInit
): Promise<T> {
  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options?.headers,
    },
  });

  if (!response.ok) {
    const errorText = await response.text();
    throw new ApiError(response.status, errorText || response.statusText);
  }

  return response.json();
}

export const api = {
  pages: {
    /**
     * List all pages
     */
    list: () => fetchApi<Page[]>('/pages'),

    /**
     * Get a single page by slug
     */
    get: (slug: string) => fetchApi<PageData>(`/pages/${slug}`),

    /**
     * Create a new page
     */
    create: (data: CreatePageInput) =>
      fetchApi<Page>('/pages', {
        method: 'POST',
        body: JSON.stringify(data),
      }),

    /**
     * Update an existing page
     */
    update: (slug: string, data: UpdatePageInput) =>
      fetchApi<Page>(`/pages/${slug}`, {
        method: 'PUT',
        body: JSON.stringify(data),
      }),

    /**
     * Delete a page
     */
    delete: (slug: string) =>
      fetchApi<void>(`/pages/${slug}`, {
        method: 'DELETE',
      }),
  },

  media: {
    /**
     * Upload an image file
     */
    upload: async (file: File): Promise<MediaUploadResponse> => {
      const formData = new FormData();
      formData.append('image', file);

      const response = await fetch(`${API_BASE_URL}/pages/media`, {
        method: 'POST',
        body: formData,
        // Don't set Content-Type header - browser will set it with boundary
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new ApiError(response.status, errorText || response.statusText);
      }

      return response.json();
    },
  },
};
