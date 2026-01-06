// Base path configuration
// - For Docker/Hetzner deployment: empty (assets at root)
// - For GitHub Pages: "/musikfuerfirmen"
// - Set via NEXT_PUBLIC_BASE_PATH environment variable
export const basePath = process.env.NEXT_PUBLIC_BASE_PATH || "";

// Helper function to get the full asset path
// This is called at build time during static generation
export function getAssetPath(path: string): string {
  // If path already starts with basePath or is an external URL, return as is
  if (path.startsWith(basePath) || path.startsWith("http")) {
    return path;
  }
  // Ensure path starts with /
  const normalizedPath = path.startsWith("/") ? path : `/${path}`;
  return `${basePath}${normalizedPath}`;
}
