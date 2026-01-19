// Note: Content comes from trusted CMS database, not user input
// TODO: Add DOMPurify sanitization for production

interface TextBlockProps {
  heading?: string;
  content?: string;
  editable?: boolean;
  _editableProps?: {
    blockId: string;
    onContentChange: (blockId: string, path: string, value: any) => void;
    isEditing: boolean;
  };
}

export default function TextBlock({
  heading = '',
  content = '',
  editable = false,
  _editableProps
}: TextBlockProps) {
  return (
    <section className="py-20 px-4">
      <div className="max-w-4xl mx-auto">
        {heading && (
          <h1
            className="text-4xl font-bold mb-8"
            {...(editable && {
              "data-editable": "heading",
              contentEditable: _editableProps?.isEditing,
              suppressContentEditableWarning: true,
              onBlur: (e) => {
                if (_editableProps) {
                  _editableProps.onContentChange(_editableProps.blockId, 'heading', e.currentTarget.textContent || '');
                }
              }
            })}
          >
            {heading}
          </h1>
        )}

        <div
          className="prose prose-lg max-w-none"
          {...(editable && {
            "data-editable": "content",
            contentEditable: _editableProps?.isEditing,
            suppressContentEditableWarning: true,
            dangerouslySetInnerHTML: !_editableProps?.isEditing ? { __html: content } : undefined,
            onBlur: (e) => {
              if (_editableProps) {
                _editableProps.onContentChange(_editableProps.blockId, 'content', e.currentTarget.innerHTML || '');
              }
            }
          })}
        >
          {(!editable || !_editableProps?.isEditing) && (
            <div dangerouslySetInnerHTML={{ __html: content }} />
          )}
        </div>
      </div>
    </section>
  );
}
