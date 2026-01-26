import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Development mode - no static export
  // (Use next.config.github-pages.ts.backup for GitHub Pages deployment)

  // Turbopack disabled - causes memory leaks in Next.js 16
  // Project is small enough (85 files) that Webpack is faster and more stable
  // Re-enable when: project >500 files OR Next.js 17+ fixes memory issues

  // Image optimization
  images: {
    formats: ["image/avif", "image/webp"],
  },

  // Exclude example files from build (handled by TypeScript exclude in tsconfig.json)
};

export default nextConfig;
