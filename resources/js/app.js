import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const QUIZ_FORM_SELECTOR = '[data-quiz-form]';
const QUIZ_PROGRESS_SELECTOR = '[data-quiz-progress]';
const QUIZ_OPTION_SELECTOR = '[data-quiz-option]';

const MEMORY_GAME_SELECTOR = '[data-memory-game]';
const MEMORY_BOARD_SELECTOR = '[data-memory-board]';
const MEMORY_CARD_SELECTOR = '[data-memory-card]';
const MEMORY_STATUS_SELECTOR = '[data-memory-status]';
const MEMORY_FEEDBACK_SELECTOR = '[data-memory-feedback]';
const MEMORY_SUBMIT_SELECTOR = '[data-memory-submit]';
const MEMORY_ACTION_SELECTOR = '[data-memory-action]';
const MEMORY_INPUT_SELECTOR = '[data-memory-input]';

const QUESTION_BUILDER_SELECTOR = '[data-question-builder]';
const QUESTION_LIST_SELECTOR = '[data-question-list]';
const QUESTION_EMPTY_SELECTOR = '[data-question-empty]';
const QUESTION_ADD_SELECTOR = '[data-question-add]';
const QUESTION_TEMPLATE_SELECTOR = 'template[data-question-template]';
const OPTION_TEMPLATE_SELECTOR = 'template[data-option-template]';

function initQuizForms() {
	const quizForms = document.querySelectorAll(QUIZ_FORM_SELECTOR);
	if (!quizForms.length) {
		return;
	}

	quizForms.forEach((form) => {
		const totalQuestions = form.querySelectorAll('[data-quiz-question]').length;
		const progressElement = form.querySelector(QUIZ_PROGRESS_SELECTOR);

		const updateProgress = () => {
			const answered = form.querySelectorAll(`${QUIZ_OPTION_SELECTOR} input:checked`).length;
			if (progressElement) {
				progressElement.textContent = `${answered} / ${totalQuestions}`;
			}
		};

		form.addEventListener('change', (event) => {
			if (!(event.target instanceof HTMLInputElement)) {
				return;
			}

			if (event.target.type === 'radio') {
				const container = event.target.closest(QUIZ_OPTION_SELECTOR);
				const groupName = event.target.name;
				if (groupName) {
					const groupInputs = form.querySelectorAll(`input[name="${CSS.escape(groupName)}"]`);
					groupInputs.forEach((input) => {
						const option = input.closest(QUIZ_OPTION_SELECTOR);
						if (!option) {
							return;
						}
						option.classList.toggle('is-selected', input.checked);
					});
				}

				if (container) {
					container.classList.add('is-selected');
				}

				updateProgress();
			}
		});

		updateProgress();
	});
}

function initMemoryGames() {
	const games = document.querySelectorAll(MEMORY_GAME_SELECTOR);
	if (!games.length) {
		return;
	}

	games.forEach((game) => {
		const board = game.querySelector(MEMORY_BOARD_SELECTOR);
		const status = game.querySelector(MEMORY_STATUS_SELECTOR);
		const feedback = game.querySelector(MEMORY_FEEDBACK_SELECTOR);
		const submitButton = game.querySelector(MEMORY_SUBMIT_SELECTOR);
		const restartButton = game.querySelector('[data-memory-action="restart"]');
		const startButton = game.querySelector('[data-memory-action="start"]');
		const inputs = game.querySelectorAll(MEMORY_INPUT_SELECTOR);
		const totalPairs = Number(game.dataset.memoryPairs || 0);

		if (!board || !startButton || !submitButton) {
			return;
		}

		const cards = Array.from(board.querySelectorAll(MEMORY_CARD_SELECTOR));
		const state = {
			active: [],
			matchedPairs: 0,
			locked: true,
			previewTimeout: null,
		};

		const resetCards = () => {
			cards.forEach((card) => {
				card.classList.remove('is-flipped', 'is-matched', 'is-disabled');
				card.disabled = false;
			});
		};

		const clearInputs = () => {
			inputs.forEach((input) => {
				input.value = '';
			});
		};

		const updateStatus = () => {
			if (status) {
				status.textContent = `${state.matchedPairs} / ${totalPairs}`;
			}

			if (submitButton) {
				submitButton.disabled = state.matchedPairs !== totalPairs;
			}
		};

		const setFeedback = (message, intent = 'info') => {
			if (feedback) {
				feedback.textContent = message;
				feedback.dataset.intent = intent;
			}
		};

		const flipCard = (card, force = true) => {
			if (force) {
				card.classList.add('is-flipped');
			} else {
				card.classList.remove('is-flipped');
			}
		};

		const hideUnmatched = () => {
			state.active.forEach((card) => {
				flipCard(card, false);
				card.classList.remove('is-disabled');
			});
			state.active = [];
		};

		const registerMatch = (questionId, optionId) => {
			const input = Array.from(inputs).find((element) => element.dataset.memoryInput === questionId);
			if (input && optionId) {
				input.value = optionId;
			}
		};

		const handleMatch = (selectedCards) => {
			selectedCards.forEach((card) => {
				card.classList.add('is-matched');
				card.classList.remove('is-disabled');
				card.disabled = true;
			});

			const answerCard = selectedCards.find((card) => card.dataset.optionId);
			const questionId = selectedCards[0].dataset.questionId;
			const optionId = answerCard ? answerCard.dataset.optionId : null;

			registerMatch(questionId, optionId);
			state.matchedPairs += 1;
			setFeedback('¡Pareja encontrada! Continúa con las cartas restantes.', 'success');
			updateStatus();

			state.active = [];
		};

		const lockTemporarily = () => {
			state.locked = true;
			setTimeout(() => {
				state.locked = false;
			}, 400);
		};

		const onCardClick = (card) => {
			if (state.locked || card.classList.contains('is-matched') || card.classList.contains('is-disabled')) {
				return;
			}

			card.classList.add('is-disabled');
			flipCard(card, true);
			state.active.push(card);

			if (state.active.length === 2) {
				const [first, second] = state.active;
				if (first.dataset.pair === second.dataset.pair) {
					handleMatch([first, second]);
				} else {
					setFeedback('No coinciden. Inténtalo de nuevo.', 'warning');
					state.locked = true;
					setTimeout(() => {
						hideUnmatched();
						state.locked = false;
					}, 900);
				}
			}
		};

		const bindCardEvents = () => {
			cards.forEach((card) => {
				card.addEventListener('click', () => onCardClick(card));
			});
		};

		const previewBoard = () => {
			cards.forEach((card) => {
				flipCard(card, true);
			});

			state.previewTimeout = window.setTimeout(() => {
				cards.forEach((card) => {
					if (!card.classList.contains('is-matched')) {
						flipCard(card, false);
					}
				});
				state.locked = false;
				setFeedback('¡A jugar! Encuentra cada pareja lo más rápido posible.', 'info');
			}, 2500);
		};

		const startGame = () => {
			if (state.previewTimeout) {
				window.clearTimeout(state.previewTimeout);
			}

			state.active = [];
			state.matchedPairs = 0;
			state.locked = true;

			clearInputs();
			resetCards();
			updateStatus();

			// ensure submit disabled until pairs found
			if (submitButton) {
				submitButton.disabled = true;
			}

			cards.forEach((card) => {
				card.classList.remove('is-hidden');
			});

			previewBoard();
			if (restartButton) {
				restartButton.hidden = false;
			}
			setFeedback('Observa las cartas durante unos segundos y memoriza las parejas.', 'info');
		};

		bindCardEvents();
		updateStatus();

		startButton.addEventListener('click', () => {
			startGame();
		});

		if (restartButton) {
			restartButton.addEventListener('click', () => {
				startGame();
			});
		}

		game.addEventListener('submit', () => {
			if (state.previewTimeout) {
				window.clearTimeout(state.previewTimeout);
			}
		});
	});
}

	function initGameQuestionBuilder() {
		const builders = document.querySelectorAll(QUESTION_BUILDER_SELECTOR);
		if (!builders.length) {
			return;
		}

		const modeMessages = {
			quiz: 'Agrega preguntas tipo quiz con múltiples opciones y marca cuál es la correcta.',
			memoria: 'Define pares de cartas ingresando el enunciado y la respuesta correcta para cada una.',
		};

		builders.forEach((builder) => {
			const questionTemplate = builder.querySelector(QUESTION_TEMPLATE_SELECTOR);
			const optionTemplate = builder.querySelector(OPTION_TEMPLATE_SELECTOR);
			const list = builder.querySelector(QUESTION_LIST_SELECTOR);
			const emptyState = builder.querySelector(QUESTION_EMPTY_SELECTOR);
			const addButton = builder.querySelector(QUESTION_ADD_SELECTOR);
			const modeHint = builder.querySelector('[data-question-mode-hint]');
			const typeSelect = document.querySelector('[data-game-type-selector]');

			if (!questionTemplate || !optionTemplate || !list || !addButton) {
				return;
			}

			let questionSequence = 0;

			const parseExistingQuestions = () => {
				const raw = builder.dataset.existingQuestions;
				if (!raw) {
					return [];
				}

				try {
					const parsed = JSON.parse(raw);
					if (!Array.isArray(parsed)) {
						return Object.values(parsed);
					}
					return parsed;
				} catch (error) {
					console.warn('No se pudieron leer las preguntas previas', error);
					return [];
				}
			};

			const getMode = () => {
				const raw = typeSelect ? typeSelect.value : builder.dataset.initialMode;
				return raw || 'quiz';
			};

			const updateModeHint = () => {
				if (modeHint) {
					const mode = getMode();
					modeHint.textContent = modeMessages[mode] || modeMessages.quiz;
				}
			};

			const toggleEmptyState = () => {
				if (!emptyState) {
					return;
				}

				const hasQuestions = list.querySelectorAll('[data-question-card]').length > 0;
				emptyState.hidden = hasQuestions;
			};

			const updateQuestionNumbers = () => {
				const cards = list.querySelectorAll('[data-question-card]');
				cards.forEach((card, index) => {
					const number = card.querySelector('[data-question-number]');
					if (number) {
						number.textContent = `#${index + 1}`;
					}
				});
			};

			const ensureOptionRemovals = (optionList) => {
				const options = optionList.querySelectorAll('[data-option-row]');
				const disableRemoval = options.length <= 2;
				options.forEach((option) => {
					const removeButton = option.querySelector('[data-option-remove]');
					if (removeButton) {
						removeButton.disabled = disableRemoval;
					}
				});
			};

			const applyModeToCard = (card) => {
				const mode = getMode();

				const sections = card.querySelectorAll('[data-question-mode]');
				sections.forEach((section) => {
					const targetMode = section.dataset.questionMode;
					const isActive = targetMode === mode;
					section.hidden = !isActive;
				});

				const optionList = card.querySelector('[data-option-list]');
				const optionInputs = optionList ? optionList.querySelectorAll('input[type="text"]') : [];
				optionInputs.forEach((input) => {
					input.required = mode === 'quiz';
					input.disabled = mode !== 'quiz';
				});

				const radios = card.querySelectorAll('input[type="radio"]');
				radios.forEach((radio) => {
					radio.required = mode === 'quiz';
					radio.disabled = mode !== 'quiz';
				});

				const optionButtons = card.querySelectorAll('[data-option-add],[data-option-remove]');
				optionButtons.forEach((button) => {
					if (button.hasAttribute('data-option-add')) {
						button.disabled = mode !== 'quiz';
					}
							if (button.hasAttribute('data-option-remove')) {
								button.disabled = mode !== 'quiz';
							}
				});

				const answerInput = card.querySelector('[data-memory-answer]');
				if (answerInput) {
					answerInput.required = mode === 'memoria';
					answerInput.disabled = mode !== 'memoria';
				}
			};

			const createOptionRow = (card, optionIndex, preset, autoCheck = false) => {
				const optionList = card.querySelector('[data-option-list]');
				if (!optionList) {
					return null;
				}

				const questionIndex = card.dataset.questionIndex;
				const markup = optionTemplate.innerHTML
					.replace(/__INDEX__/g, String(questionIndex))
					.replace(/__OPTION_INDEX__/g, String(optionIndex));

				const wrapper = document.createElement('div');
				wrapper.innerHTML = markup.trim();
				const optionRow = wrapper.firstElementChild;
				optionList.appendChild(optionRow);

				const textInput = optionRow.querySelector('input[type="text"]');
				const radioInput = optionRow.querySelector('input[type="radio"]');
				const removeButton = optionRow.querySelector('[data-option-remove]');

				if (textInput) {
					textInput.value = preset && typeof preset.text === 'string' ? preset.text : '';
				}

				if (radioInput) {
					const shouldCheck = autoCheck || (preset && preset.is_correct);
					radioInput.checked = Boolean(shouldCheck);
				}

				if (removeButton) {
					removeButton.addEventListener('click', () => {
						if (optionList.querySelectorAll('[data-option-row]').length <= 2) {
							return;
						}
						optionRow.remove();
						ensureOptionRemovals(optionList);
					});
				}

				return optionRow;
			};

			const mountQuestionCard = (preset = null) => {
				const index = questionSequence++;
				const markup = questionTemplate.innerHTML.replace(/__INDEX__/g, String(index));
				const wrapper = document.createElement('div');
				wrapper.innerHTML = markup.trim();
				const card = wrapper.firstElementChild;
				if (!card) {
					return null;
				}

				card.dataset.questionIndex = String(index);
				card.dataset.optionSequence = '0';

				const statementInput = card.querySelector(`input[name="questions[${index}][statement]"]`);
				if (statementInput && preset && typeof preset.statement === 'string') {
					statementInput.value = preset.statement;
				}

				const optionList = card.querySelector('[data-option-list]');
				if (optionList) {
					optionList.innerHTML = '';
				}

				const presetOptions = Array.isArray(preset?.options) ? preset.options : [];
				if (presetOptions.length) {
					presetOptions.forEach((option) => {
						const optionSequence = Number(card.dataset.optionSequence || '0');
						createOptionRow(card, optionSequence, option, option.is_correct);
						card.dataset.optionSequence = String(optionSequence + 1);
					});
				} else {
					for (let step = 0; step < 3; step += 1) {
						const optionSequence = Number(card.dataset.optionSequence || '0');
						createOptionRow(card, optionSequence, null, step === 0);
						card.dataset.optionSequence = String(optionSequence + 1);
					}
				}

				if (optionList) {
							if (!optionList.querySelector('input[type="radio"]:checked')) {
								const fallbackRadio = optionList.querySelector('input[type="radio"]');
								if (fallbackRadio) {
									fallbackRadio.checked = true;
								}
							}
					ensureOptionRemovals(optionList);
				}

				const answerInput = card.querySelector('[data-memory-answer]');
				if (answerInput && preset && typeof preset.answer === 'string') {
					answerInput.value = preset.answer;
				}

				const removeButton = card.querySelector('[data-question-remove]');
				if (removeButton) {
					removeButton.addEventListener('click', () => {
						card.remove();
						updateQuestionNumbers();
						toggleEmptyState();
					});
				}

				const optionAddButton = card.querySelector('[data-option-add]');
				if (optionAddButton) {
					optionAddButton.addEventListener('click', () => {
						const optionSequence = Number(card.dataset.optionSequence || '0');
						createOptionRow(card, optionSequence, null, false);
						card.dataset.optionSequence = String(optionSequence + 1);
						const optionListRef = card.querySelector('[data-option-list]');
						if (optionListRef) {
							ensureOptionRemovals(optionListRef);
						}
					});
				}

				list.appendChild(card);
				updateQuestionNumbers();
				toggleEmptyState();
				applyModeToCard(card);

				return card;
			};

			const buildPresetFromRaw = (rawQuestion) => {
				if (!rawQuestion || typeof rawQuestion !== 'object') {
					return {};
				}

				const statement = typeof rawQuestion.statement === 'string' ? rawQuestion.statement : '';
				const answer = typeof rawQuestion.answer === 'string' ? rawQuestion.answer : '';
				const optionsContainer = rawQuestion.options || [];
				const correctKey = rawQuestion.correct_option ?? rawQuestion.correctOption ?? null;
				const presetOptions = [];

				if (optionsContainer && typeof optionsContainer === 'object') {
					const entries = Array.isArray(optionsContainer)
						? optionsContainer.entries()
						: Object.entries(optionsContainer);

					for (const [key, value] of entries) {
						const optionText = value && typeof value.text === 'string' ? value.text : '';
						presetOptions.push({
							text: optionText,
							is_correct: correctKey !== null && String(correctKey) === String(key),
						});
					}
				}

				return {
					statement,
					answer,
					options: presetOptions,
				};
			};

			const loadExisting = () => {
				const existing = parseExistingQuestions();
				if (!existing.length) {
					toggleEmptyState();
					updateModeHint();
					return;
				}

				existing.forEach((rawQuestion) => {
					const preset = buildPresetFromRaw(rawQuestion);
					mountQuestionCard(preset);
				});

				toggleEmptyState();
				updateModeHint();
			};

			addButton.addEventListener('click', () => {
				mountQuestionCard();
			});

			if (typeSelect) {
				typeSelect.addEventListener('change', () => {
					updateModeHint();
					const cards = list.querySelectorAll('[data-question-card]');
					cards.forEach((card) => applyModeToCard(card));
				});
			}

			loadExisting();
			updateModeHint();
		});
	}

document.addEventListener('DOMContentLoaded', () => {
	initQuizForms();
	initMemoryGames();
		initGameQuestionBuilder();
});

Alpine.start();
