import csv
import unicodedata

def remover_acentos(texto):
    return ''.join(
        c for c in unicodedata.normalize('NFD', texto)
        if unicodedata.category(c) != 'Mn'
    )

frase = input("Digite a frase do usuário: ").strip()
intencao = input("Qual é a intenção correta?: ").strip()

# Remove acentos e coloca em minúsculas
frase = remover_acentos(frase.lower())

with open('dados1.csv', mode='a', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow([frase, intencao])

print("✅ Frase adicionada com sucesso. Agora execute 'treinar_modelo.py' para atualizar o modelo.")
