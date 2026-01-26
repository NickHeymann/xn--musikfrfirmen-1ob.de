"use client";

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useEditorAuth } from '@/hooks/useEditorAuth';

export default function EditorLoginPage() {
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const router = useRouter();
  const { login } = useEditorAuth();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const success = login(password);

    if (success) {
      router.push('/admin/editor');
    } else {
      setError('Invalid password');
      setPassword('');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Visual Editor Login
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            musikfürfirmen.de
          </p>
        </div>

        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          <div>
            <label htmlFor="password" className="sr-only">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              required
              className="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#2DD4A8] focus:border-[#2DD4A8] focus:z-10 sm:text-sm"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          {error && (
            <div className="text-red-600 text-sm text-center">
              {error}
            </div>
          )}

          <div>
            <button
              type="submit"
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#2DD4A8] hover:bg-[#22a883] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2DD4A8]"
            >
              Sign in
            </button>
          </div>
        </form>

        <div className="text-center text-xs text-gray-500">
          POC: Use password "admin123"
        </div>
      </div>
    </div>
  );
}
