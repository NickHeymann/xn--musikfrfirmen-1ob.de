import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Development mode - no static export
  // (Use next.config.github-pages.ts.backup for GitHub Pages deployment)

  // Turbopack root directory fix
  turbopack: {
    root: process.cwd(),
  },

  // Image optimization
  images: {
    formats: ["image/avif", "image/webp"],
  },
};

export default nextConfig;
