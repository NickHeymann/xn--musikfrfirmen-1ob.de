/**
 * SkeletonLoader Component - Placeholder for loading content
 * Used during page load and block data fetching
 */

interface SkeletonLoaderProps {
  width?: string | number; // CSS width (e.g., '100%', 200)
  height?: string | number; // CSS height (e.g., '100%', 400)
  rounded?: boolean; // Rounded corners
  animate?: boolean; // Pulse animation
  className?: string;
}

export function SkeletonLoader({
  width = "100%",
  height = 200,
  rounded = false,
  animate = true,
  className = "",
}: SkeletonLoaderProps) {
  const widthStyle = typeof width === "number" ? `${width}px` : width;
  const heightStyle = typeof height === "number" ? `${height}px` : height;

  return (
    <div
      className={`skeleton ${animate ? "skeleton-animate" : ""} ${rounded ? "skeleton-rounded" : ""} ${className}`}
      style={{
        width: widthStyle,
        height: heightStyle,
      }}
      aria-busy="true"
      aria-label="Loading content"
    />
  );
}
