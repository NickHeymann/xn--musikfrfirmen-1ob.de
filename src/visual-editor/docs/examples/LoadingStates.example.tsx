/**
 * Loading States Examples
 *
 * This file demonstrates how to use Spinner and SkeletonLoader components
 * in the visual editor.
 */

import { Spinner } from "./Spinner";
import { SkeletonLoader } from "./SkeletonLoader";

export function LoadingStatesExample() {
  return (
    <div style={{ padding: "40px" }}>
      <h2>Spinner Examples</h2>

      <div style={{ marginBottom: "30px" }}>
        <h3>Small (16px)</h3>
        <Spinner size="sm" />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Medium (24px)</h3>
        <Spinner size="md" />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Large (32px)</h3>
        <Spinner size="lg" />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Custom Color</h3>
        <Spinner size="md" color="#FF3B30" />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>In Button</h3>
        <button style={{ display: "flex", alignItems: "center", gap: "8px" }}>
          <Spinner size="sm" />
          Saving...
        </button>
      </div>

      <h2>Skeleton Loader Examples</h2>

      <div style={{ marginBottom: "30px" }}>
        <h3>Hero Block Skeleton</h3>
        <SkeletonLoader height={400} rounded animate />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Service Card Skeleton</h3>
        <SkeletonLoader height={300} rounded animate />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Text Block Skeleton</h3>
        <SkeletonLoader height={100} animate />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Multiple Skeletons (Page Loading)</h3>
        <SkeletonLoader height={400} rounded animate />
        <div style={{ marginTop: "20px" }}>
          <SkeletonLoader height={300} rounded animate />
        </div>
        <div style={{ marginTop: "20px" }}>
          <SkeletonLoader height={200} rounded animate />
        </div>
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Static Skeleton (No Animation)</h3>
        <SkeletonLoader height={200} animate={false} />
      </div>

      <div style={{ marginBottom: "30px" }}>
        <h3>Custom Width & Height</h3>
        <SkeletonLoader width={300} height={100} rounded animate />
      </div>
    </div>
  );
}

/**
 * Usage in Real Components:
 *
 * 1. Save Button with Loading State:
 * ```tsx
 * <button onClick={handleSave} disabled={isSaving}>
 *   {isSaving ? (
 *     <>
 *       <Spinner size="sm" />
 *       Saving...
 *     </>
 *   ) : (
 *     'Save Changes'
 *   )}
 * </button>
 * ```
 *
 * 2. Page Load with Skeletons:
 * ```tsx
 * {isLoading ? (
 *   <div className="skeleton-container">
 *     <SkeletonLoader height={400} rounded animate />
 *     <SkeletonLoader height={300} rounded animate />
 *   </div>
 * ) : (
 *   <PageContent blocks={blocks} />
 * )}
 * ```
 *
 * 3. Block Editor Loading:
 * ```tsx
 * {isLoadingBlock ? (
 *   <div style={{ padding: '20px' }}>
 *     <Spinner size="md" />
 *     <p>Loading block editor...</p>
 *   </div>
 * ) : (
 *   <BlockEditor />
 * )}
 * ```
 *
 * 4. Media Upload Loading:
 * ```tsx
 * {isUploading ? (
 *   <div>
 *     <Spinner size="sm" />
 *     <span>Uploading {uploadProgress}%...</span>
 *   </div>
 * ) : (
 *   <MediaUploader />
 * )}
 * ```
 */
