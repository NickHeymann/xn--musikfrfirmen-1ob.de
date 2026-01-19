'use client';

// Admin Pages List
import { useState, useEffect } from 'react';
import { api } from '@/lib/api/client';
import { useRouter } from 'next/navigation';
import type { Page } from '@/types/visual-editor';
import { Plus, Edit, Trash2, ExternalLink } from 'lucide-react';

export default function PagesListPage() {
  const router = useRouter();
  const [pages, setPages] = useState<Page[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    loadPages();
  }, []);

  const loadPages = async () => {
    try {
      const data = await api.pages.list();
      setPages(data);
    } catch (error) {
      console.error('Failed to load pages:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const handleCreatePage = async () => {
    const title = prompt('Enter page title:');
    if (!title) return;

    try {
      const newPage = await api.pages.create({
        title,
        content: {
          version: '1.0',
          type: 'page',
          blocks: [],
        },
      });

      router.push(`/admin/editor/${newPage.slug}`);
    } catch (error) {
      console.error('Failed to create page:', error);
      alert('Failed to create page');
    }
  };

  const handleDeletePage = async (slug: string) => {
    if (!confirm('Are you sure you want to delete this page?')) return;

    try {
      await api.pages.delete(slug);
      setPages(pages.filter((p) => p.slug !== slug));
    } catch (error) {
      console.error('Failed to delete page:', error);
      alert('Failed to delete page');
    }
  };

  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2DD4A8]"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto px-6 py-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Pages</h1>
            <p className="text-gray-600 mt-1">Manage your website pages</p>
          </div>
          <button
            onClick={handleCreatePage}
            className="flex items-center gap-2 px-4 py-2 bg-[#2DD4A8] text-white rounded-lg hover:bg-[#25B88E] transition-colors"
          >
            <Plus className="w-5 h-5" />
            <span className="font-medium">New Page</span>
          </button>
        </div>

        {pages.length === 0 ? (
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div className="text-6xl mb-4">📄</div>
            <h2 className="text-xl font-semibold text-gray-900 mb-2">No pages yet</h2>
            <p className="text-gray-600 mb-6">Get started by creating your first page</p>
            <button
              onClick={handleCreatePage}
              className="inline-flex items-center gap-2 px-6 py-3 bg-[#2DD4A8] text-white rounded-lg hover:bg-[#25B88E] transition-colors"
            >
              <Plus className="w-5 h-5" />
              <span className="font-medium">Create First Page</span>
            </button>
          </div>
        ) : (
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Title
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Slug
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Last Updated
                  </th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {pages.map((page) => (
                  <tr key={page.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">{page.title}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500">/{page.slug}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500">
                        {new Date(page.updated_at).toLocaleDateString()}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div className="flex items-center justify-end gap-2">
                        <a
                          href={`/${page.slug}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-gray-600 hover:text-gray-900 p-2"
                          title="View page"
                        >
                          <ExternalLink className="w-4 h-4" />
                        </a>
                        <button
                          onClick={() => router.push(`/admin/editor/${page.slug}`)}
                          className="text-[#2DD4A8] hover:text-[#25B88E] p-2"
                          title="Edit page"
                        >
                          <Edit className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDeletePage(page.slug)}
                          className="text-red-600 hover:text-red-700 p-2"
                          title="Delete page"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}
