import { CheckIcon } from "@/components/icons";

interface ContactSuccessProps {
  onClose: () => void;
}

export default function ContactSuccess({ onClose }: ContactSuccessProps) {
  return (
    <div className="text-center py-8">
      <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <CheckIcon className="w-8 h-8 text-green-600" />
      </div>
      <h3 className="text-2xl font-semibold text-gray-900 mb-3">
        Anfrage gesendet!
      </h3>
      <p className="text-gray-600 mb-2">
        Vielen Dank! Wir melden uns in Kürze bei dir.
      </p>
      <p className="text-gray-600 mb-8">
        In 48 Stunden erhältst du ein detailliertes Angebot.
      </p>
      <button
        onClick={onClose}
        className="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer bg-[#B2EAD8] text-[#292929] hover:bg-[#7dc9b1]"
        style={{ fontFamily: "'Poppins', sans-serif" }}
      >
        Alles klar!
      </button>
    </div>
  );
}
