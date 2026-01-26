<phase name="Wave 2: Type Safety">

    <task type="auto">
      <name>Define EditableValue type in visual-editor.ts</name>
      <files>src/types/visual-editor.ts</files>
      <action>Add after line 2, following the comment: Define export type EditableValue with JSDoc explaining it supports string for text content, string array for arrays like sliderContent, and Record string unknown for complex objects</action>
      <verify>grep -q "export type EditableValue" src/types/visual-editor.ts</verify>
    </task>

    <task type="auto">
      <name>Import EditableValue in Hero.tsx</name>
      <files>src/components/Hero.tsx</files>
      <action>Add import after the React imports around line 4: import EditableValue from the visual-editor types file</action>
      <verify>grep -q "import.*EditableValue.*from.*visual-editor" src/components/Hero.tsx</verify>
    </task>

    <task type="auto">
      <name>Replace any type with EditableValue in Hero.tsx</name>
      <files>src/components/Hero.tsx</files>
      <action>On line 14, change the onContentChange parameter type from any to EditableValue for the value parameter</action>
      <verify>grep -q "onContentChange.*value: EditableValue" src/components/Hero.tsx</verify>
    </task>

    <task type="auto">
      <name>Import EditableValue in TextBlock.tsx</name>
      <files>src/components/TextBlock.tsx</files>
      <action>Add import after the DOMPurify import around line 5: import EditableValue from the visual-editor types file</action>
      <verify>grep -q "import.*EditableValue.*from.*visual-editor" src/components/TextBlock.tsx</verify>
    </task>

    <task type="auto">
      <name>Replace any type with EditableValue in TextBlock.tsx</name>
      <files>src/components/TextBlock.tsx</files>
      <action>On line 10, change the onContentChange parameter type from any to EditableValue for the value parameter</action>
      <verify>grep -q "onContentChange.*value: EditableValue" src/components/TextBlock.tsx</verify>
    </task>

    <task type="auto">
      <name>Verify TypeScript compilation passes</name>
      <files>.</files>
      <action>Run TypeScript compiler in no-emit mode to verify all type changes are correct and no errors are introduced.</action>
      <verify>npx tsc --noEmit</verify>
    </task>

    <task type="auto">
      <name>Verify ESLint passes</name>
      <files>.</files>
      <action>Run ESLint to verify code quality standards are met and no linting errors are introduced.</action>
      <verify>npm run lint</verify>
    </task>

  </phase>
